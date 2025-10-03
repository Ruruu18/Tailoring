<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\SmsLog;

class SemaphoreService
{
    protected $client;
    protected $apiKey;
    protected $senderId;
    protected $baseUrl = 'https://api.semaphore.co';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.semaphore.api_key');
        $this->senderId = config('services.semaphore.sender_id', 'SEMAPHORE');
    }

    public function sendSms($phoneNumber, $message)
    {
        if (!$this->apiKey) {
            Log::error('Semaphore API key not configured');
            return [
                'success' => false,
                'error' => 'API key not configured'
            ];
        }

        $phoneNumber = $this->formatPhoneNumber($phoneNumber);

        if (!$phoneNumber) {
            return [
                'success' => false,
                'error' => 'Invalid phone number format'
            ];
        }

        try {
            $response = $this->client->post($this->baseUrl . '/api/v4/messages', [
                'form_params' => [
                    'apikey' => $this->apiKey,
                    'number' => $phoneNumber,
                    'message' => $message,
                    'sendername' => $this->senderId
                ],
                'timeout' => 30
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200) {
                $this->logSms($phoneNumber, $message, 'sent', $body);
                return [
                    'success' => true,
                    'response' => $body,
                    'message_id' => $body[0]['message_id'] ?? null
                ];
            }

            $this->logSms($phoneNumber, $message, 'failed', $body);
            return [
                'success' => false,
                'error' => 'API returned non-200 status',
                'response' => $body
            ];

        } catch (RequestException $e) {
            $error = $e->hasResponse() ? $e->getResponse()->getBody() : $e->getMessage();
            Log::error('Semaphore SMS failed', [
                'phone' => $phoneNumber,
                'message' => $message,
                'error' => $error
            ]);

            $this->logSms($phoneNumber, $message, 'failed', ['error' => $error]);

            return [
                'success' => false,
                'error' => 'Request failed: ' . $error
            ];
        } catch (\Exception $e) {
            Log::error('Semaphore SMS exception', [
                'phone' => $phoneNumber,
                'message' => $message,
                'error' => $e->getMessage()
            ]);

            $this->logSms($phoneNumber, $message, 'failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    public function sendBulkSms($recipients, $message)
    {
        $results = [];

        foreach ($recipients as $recipient) {
            $phoneNumber = is_array($recipient) ? $recipient['phone'] : $recipient;
            $results[] = $this->sendSms($phoneNumber, $message);
        }

        return $results;
    }

    public function getAccountBalance()
    {
        try {
            $response = $this->client->get($this->baseUrl . '/api/v4/account', [
                'query' => ['apikey' => $this->apiKey],
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return null;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 429) {
                Log::warning('Semaphore API rate limit reached', ['status' => 429]);
                return ['error' => 'Rate limit reached. Please try again later.'];
            }
            Log::error('Failed to get Semaphore account balance', ['error' => $e->getMessage()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get Semaphore account balance', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function getMessageStatus($messageId)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/api/v4/messages/' . $messageId, [
                'query' => ['apikey' => $this->apiKey],
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get message status', ['message_id' => $messageId, 'error' => $e->getMessage()]);
            return null;
        }
    }

    protected function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        if (strpos($phoneNumber, '+') === 0) {
            return $phoneNumber;
        }

        if (strpos($phoneNumber, '63') === 0) {
            return '+' . $phoneNumber;
        }

        if (strpos($phoneNumber, '09') === 0) {
            return '+63' . substr($phoneNumber, 1);
        }

        if (strpos($phoneNumber, '9') === 0 && strlen($phoneNumber) === 10) {
            return '+63' . $phoneNumber;
        }

        return null;
    }

    protected function logSms($phoneNumber, $message, $status, $response = null)
    {
        try {
            SmsLog::create([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'status' => $status,
                'response' => is_array($response) ? json_encode($response) : $response,
                'sent_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log SMS', ['error' => $e->getMessage()]);
        }
    }

    public function validateApiKey()
    {
        try {
            $response = $this->client->get($this->baseUrl . '/api/v4/account', [
                'query' => ['apikey' => $this->apiKey],
                'timeout' => 10
            ]);

            return $response->getStatusCode() === 200;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 429) {
                // Rate limited, but API key might still be valid
                Log::warning('API validation rate limited');
                return null; // Return null to indicate rate limit, not invalid key
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}