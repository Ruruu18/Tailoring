<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SemaphoreService;
use App\Models\SmsLog;
use App\Models\User;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SemaphoreService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $smsLogs = SmsLog::latest('sent_at')->paginate(20);
        $stats = [
            'total_sent' => SmsLog::successful()->count(),
            'total_failed' => SmsLog::failed()->count(),
            'this_month' => SmsLog::whereMonth('sent_at', now()->month)->count(),
            'balance' => $this->smsService->getAccountBalance()
        ];

        return view('admin.sms.index', compact('smsLogs', 'stats'));
    }

    public function compose()
    {
        $customers = User::where('role', 'customer')
            ->whereNotNull('phone')
            ->orderBy('name')
            ->get();

        return view('admin.sms.compose', compact('customers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'message' => 'required|string|max:500',
        ]);

        $users = User::whereIn('id', $request->recipients)->get();
        $results = [];

        foreach ($users as $user) {
            if ($user->phone) {
                $result = $this->smsService->sendSms($user->phone, $request->message);
                $results[] = [
                    'user' => $user->name,
                    'phone' => $user->phone,
                    'success' => $result['success'],
                    'error' => $result['error'] ?? null
                ];
            }
        }

        $successCount = collect($results)->where('success', true)->count();
        $failCount = collect($results)->where('success', false)->count();

        if ($successCount > 0) {
            session()->flash('success', "SMS sent successfully to {$successCount} recipients.");
        }

        if ($failCount > 0) {
            session()->flash('warning', "Failed to send SMS to {$failCount} recipients.");
        }

        return redirect()->route('admin.sms.index')->with('results', $results);
    }

    public function show(SmsLog $smsLog)
    {
        return view('admin.sms.show', compact('smsLog'));
    }

    public function settings()
    {
        $balance = $this->smsService->getAccountBalance();
        $isConfigured = !empty(config('services.semaphore.api_key'));
        $validationResult = $isConfigured ? $this->smsService->validateApiKey() : false;

        // Handle rate limiting case
        $isValidKey = $validationResult === true;
        $isRateLimited = $validationResult === null;

        return view('admin.sms.settings', compact('balance', 'isConfigured', 'isValidKey', 'isRateLimited'));
    }

    public function testConnection()
    {
        $validationResult = $this->smsService->validateApiKey();

        if ($validationResult === true) {
            return response()->json([
                'success' => true,
                'message' => 'API key is valid and connection successful.'
            ]);
        }

        if ($validationResult === null) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit reached. Please wait a moment and try again.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid API key or connection failed.'
        ]);
    }

    public function bulkSend()
    {
        $customers = User::where('role', 'customer')
            ->whereNotNull('phone')
            ->with(['orders' => function($query) {
                $query->whereIn('status', ['pending', 'in_progress', 'ready']);
            }])
            ->get();

        return view('admin.sms.bulk', compact('customers'));
    }

    public function sendBulk(Request $request)
    {
        $request->validate([
            'filter' => 'required|in:all,active_orders,no_phone,custom',
            'message' => 'required|string|max:500',
            'custom_recipients' => 'required_if:filter,custom|array',
        ]);

        $query = User::where('role', 'customer')->whereNotNull('phone');

        switch ($request->filter) {
            case 'active_orders':
                $query->whereHas('orders', function($q) {
                    $q->whereIn('status', ['pending', 'in_progress', 'ready']);
                });
                break;
            case 'custom':
                $query->whereIn('id', $request->custom_recipients ?? []);
                break;
        }

        $users = $query->get();
        $results = $this->smsService->sendBulkSms($users->pluck('phone')->toArray(), $request->message);

        $successCount = collect($results)->where('success', true)->count();
        $failCount = collect($results)->where('success', false)->count();

        session()->flash('success', "Bulk SMS sent to {$successCount} recipients, {$failCount} failed.");

        return redirect()->route('admin.sms.index');
    }
}
