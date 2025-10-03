<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PayMongoService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

    /**
     * Initiate GCash payment
     */
    public function payWithGCash(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be paid (has outstanding balance and is not pending)
        if ($order->paid_amount >= $order->total_amount || $order->total_amount <= 0 || $order->status === 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order cannot be paid at this time. Please ensure the order has been quoted first.');
        }

        $amount = $order->total_amount - $order->paid_amount; // Remaining amount
        $redirectUrl = route('payment.callback', ['order' => $order->id, 'method' => 'gcash']);

        // Create GCash source
        $source = $this->payMongoService->createGCashSource(
            $amount,
            $redirectUrl,
            "Payment for Order #{$order->order_number}"
        );

        if (!$source) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to initialize GCash payment. Please try again.');
        }

        // Store source ID in session for callback verification
        session(['payment_source_id' => $source['data']['id']]);
        session(['payment_order_id' => $order->id]);
        session(['payment_method' => 'gcash']);

        // Get the checkout URL
        $checkoutUrl = $source['data']['attributes']['redirect']['checkout_url'];

        Log::info('GCash Payment Initiated', [
            'order_id' => $order->id,
            'amount' => $amount,
            'source_id' => $source['data']['id'],
            'checkout_url' => $checkoutUrl
        ]);

        return redirect($checkoutUrl);
    }

    /**
     * Initiate PayMaya payment
     */
    public function payWithPayMaya(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be paid (has outstanding balance and is not pending)
        if ($order->paid_amount >= $order->total_amount || $order->total_amount <= 0 || $order->status === 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order cannot be paid at this time. Please ensure the order has been quoted first.');
        }

        $amount = $order->total_amount - $order->paid_amount; // Remaining amount
        $redirectUrl = route('payment.callback', ['order' => $order->id, 'method' => 'paymaya']);

        // Create PayMaya payment method
        $paymentMethod = $this->payMongoService->createPayMayaPaymentMethod(
            $amount,
            $redirectUrl,
            "Payment for Order #{$order->order_number}"
        );

        if (!$paymentMethod) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to initialize PayMaya payment. Please try again.');
        }

        // Create payment intent
        $paymentIntent = $this->payMongoService->createPaymentIntent(
            $amount,
            'PHP',
            "Payment for Order #{$order->order_number}"
        );

        if (!$paymentIntent) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to create payment intent. Please try again.');
        }

        // Attach payment method to payment intent
        $attachment = $this->payMongoService->attachPaymentMethod(
            $paymentIntent['data']['id'],
            $paymentMethod['data']['id'],
            $redirectUrl
        );

        if (!$attachment) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to attach payment method. Please try again.');
        }

        // Store payment intent ID in session for callback verification
        session(['payment_intent_id' => $paymentIntent['data']['id']]);
        session(['payment_method_id' => $paymentMethod['data']['id']]);
        session(['payment_order_id' => $order->id]);
        session(['payment_method' => 'paymaya']);

        // Get the checkout URL from the attachment
        $checkoutUrl = $attachment['data']['attributes']['next_action']['redirect']['url'];

        Log::info('PayMaya Payment Initiated', [
            'order_id' => $order->id,
            'amount' => $amount,
            'payment_method_id' => $paymentMethod['data']['id'],
            'checkout_url' => $checkoutUrl
        ]);

        return redirect($checkoutUrl);
    }

    /**
     * Handle payment callback from PayMongo
     */
    public function paymentCallback(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $status = $request->get('status');
        $paymentMethod = session('payment_method', $request->get('method'));
        $sourceId = session('payment_source_id');
        $paymentMethodId = session('payment_method_id');
        $paymentIntentId = session('payment_intent_id');

        Log::info('Payment Callback Received', [
            'order_id' => $orderId,
            'status' => $status,
            'method' => $paymentMethod,
            'source_id' => $sourceId,
            'payment_method_id' => $paymentMethodId,
            'payment_intent_id' => $paymentIntentId,
            'all_request_params' => $request->all()
        ]);

        // Check payment status directly from PayMongo instead of relying on status parameter
        $paidAmount = 0;
        $paymentVerified = false;

        // Handle GCash (Source API)
        if ($sourceId) {
            $source = $this->payMongoService->retrieveSource($sourceId);
            Log::info('GCash Source Retrieved', ['source_data' => $source]);

            if ($source) {
                $sourceStatus = $source['data']['attributes']['status'];

                if ($sourceStatus === 'chargeable') {
                    // Source is chargeable, we need to create a payment to charge it
                    $amount = $source['data']['attributes']['amount'] / 100;
                    $payment = $this->payMongoService->createPayment(
                        $sourceId,
                        $amount,
                        "Payment for Order #{$order->order_number}"
                    );

                    Log::info('GCash Payment Created', ['payment_data' => $payment]);

                    if ($payment && $payment['data']['attributes']['status'] === 'paid') {
                        $paidAmount = $payment['data']['attributes']['amount'] / 100;
                        $paymentVerified = true;

                        Log::info('GCash Payment Verified', [
                            'payment_status' => $payment['data']['attributes']['status'],
                            'amount' => $paidAmount
                        ]);
                    }
                } elseif (in_array($sourceStatus, ['consumed', 'paid'])) {
                    // Source already consumed/paid
                    $paidAmount = $source['data']['attributes']['amount'] / 100;
                    $paymentVerified = true;

                    Log::info('GCash Payment Already Processed', [
                        'source_status' => $sourceStatus,
                        'amount' => $paidAmount
                    ]);
                }
            }
        }

        // Handle PayMaya (Payment Intent API)
        elseif ($paymentIntentId) {
            $paymentIntent = $this->payMongoService->retrievePaymentIntent($paymentIntentId);
            Log::info('PayMaya Payment Intent Retrieved', ['payment_intent_data' => $paymentIntent]);

            if ($paymentIntent) {
                $intentStatus = $paymentIntent['data']['attributes']['status'];
                if ($intentStatus === 'succeeded') {
                    $paidAmount = $paymentIntent['data']['attributes']['amount'] / 100;
                    $paymentVerified = true;

                    Log::info('PayMaya Payment Verified', [
                        'intent_status' => $intentStatus,
                        'amount' => $paidAmount
                    ]);
                }
            }
        }

        if ($paymentVerified && $paidAmount > 0) {
            // Payment successful - update order
            $oldPaidAmount = $order->paid_amount;
            $newPaidAmount = $order->paid_amount + $paidAmount;
            $isFullyPaid = $newPaidAmount >= $order->total_amount;
            $paymentType = session('payment_type', 'full'); // Get payment type from session

            // Determine new status and payment_status based on order type and current status
            $newStatus = $order->status;
            $newPaymentStatus = $order->payment_status;

            if ($order->design_type === 'pre_made' && $order->status === 'pending') {
                // Pre-made orders move from pending to quoted after first payment
                $newStatus = 'quoted';
            } elseif ($isFullyPaid) {
                $newStatus = 'paid';
                $newPaymentStatus = 'paid';
            } else {
                // Partial payment received
                if ($paymentType === 'downpayment') {
                    $newPaymentStatus = 'partial';
                } else {
                    $newStatus = 'partial_payment';
                }
            }

            $order->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newStatus,
                'payment_status' => $newPaymentStatus
            ]);

            // Send payment notification
            $paymentAmount = $newPaidAmount - $oldPaidAmount;
            NotificationService::paymentReceived($order, $paymentAmount);

            // Clear session
            session()->forget(['payment_source_id', 'payment_method_id', 'payment_intent_id', 'payment_order_id', 'payment_method', 'payment_type', 'payment_amount']);

            Log::info('Payment Successful', [
                'order_id' => $orderId,
                'paid_amount' => $paidAmount,
                'new_status' => $order->status
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', "Payment successful! Paid ₱" . number_format($paidAmount, 2) . " via " . ucfirst($paymentMethod) . ".");
        }

        // Payment failed or was cancelled
        session()->forget(['payment_source_id', 'payment_method_id', 'payment_intent_id', 'payment_order_id', 'payment_method', 'payment_type', 'payment_amount']);

        Log::warning('Payment Failed or Cancelled', [
            'order_id' => $orderId,
            'status' => $status,
            'method' => $paymentMethod
        ]);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment was not completed. Please try again.');
    }

    /**
     * Handle PayMongo webhooks
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('PayMongo-Signature');

        // Verify webhook signature (recommended for production)
        // $expectedSignature = hash_hmac('sha256', $payload, config('paymongo.webhook_secret'));

        $event = json_decode($payload, true);

        Log::info('PayMongo Webhook Received', [
            'event_type' => $event['data']['attributes']['type'] ?? 'unknown',
            'event_data' => $event
        ]);

        // Handle different webhook events
        switch ($event['data']['attributes']['type']) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event['data']['attributes']['data']);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event['data']['attributes']['data']);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSucceeded($paymentData)
    {
        // Additional processing for successful payments
        Log::info('Payment Succeeded Webhook', ['payment_data' => $paymentData]);
    }

    private function handlePaymentFailed($paymentData)
    {
        // Additional processing for failed payments
        Log::info('Payment Failed Webhook', ['payment_data' => $paymentData]);
    }

    /**
     * Process downpayment with GCash
     */
    public function downpaymentWithGCash(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate downpayment amount
        $request->validate([
            'downpayment_amount' => 'required|numeric|min:1|max:' . ($order->total_amount - 1),
        ]);

        // Check if downpayment is allowed
        if ($order->payment_status !== 'pending' || $order->paid_amount > 0 || $order->total_amount <= 0) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Downpayment is not available for this order.');
        }

        $amount = $request->downpayment_amount;
        $redirectUrl = route('payment.callback', ['order' => $order->id, 'method' => 'gcash', 'type' => 'downpayment']);

        // Create GCash source for downpayment
        $source = $this->payMongoService->createGCashSource(
            $amount,
            $redirectUrl,
            "Downpayment for Order #{$order->order_number}"
        );

        if (!$source) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to initialize GCash downpayment. Please try again.');
        }

        // Store payment details in session
        session([
            'payment_source_id' => $source['data']['id'],
            'payment_order_id' => $order->id,
            'payment_method' => 'gcash',
            'payment_type' => 'downpayment',
            'payment_amount' => $amount,
        ]);

        // Redirect to GCash
        return redirect($source['data']['attributes']['redirect']['checkout_url']);
    }

    /**
     * Process downpayment with PayMaya
     */
    public function downpaymentWithPayMaya(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate downpayment amount
        $request->validate([
            'downpayment_amount' => 'required|numeric|min:1|max:' . ($order->total_amount - 1),
        ]);

        // Check if downpayment is allowed
        if ($order->payment_status !== 'pending' || $order->paid_amount > 0 || $order->total_amount <= 0) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Downpayment is not available for this order.');
        }

        $amount = $request->downpayment_amount;
        $redirectUrl = route('payment.callback', ['order' => $order->id, 'method' => 'paymaya', 'type' => 'downpayment']);

        // Create PayMaya source for downpayment
        $source = $this->payMongoService->createPayMayaSource(
            $amount,
            $redirectUrl,
            "Downpayment for Order #{$order->order_number}"
        );

        if (!$source) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to initialize PayMaya downpayment. Please try again.');
        }

        // Store payment details in session
        session([
            'payment_source_id' => $source['data']['id'],
            'payment_order_id' => $order->id,
            'payment_method' => 'paymaya',
            'payment_type' => 'downpayment',
            'payment_amount' => $amount,
        ]);

        // Redirect to PayMaya
        return redirect($source['data']['attributes']['redirect']['checkout_url']);
    }
}