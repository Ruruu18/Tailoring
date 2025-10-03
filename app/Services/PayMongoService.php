<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('paymongo.public_key');
        $this->secretKey = config('paymongo.secret_key');
        $this->baseUrl = 'https://api.paymongo.com/v1';
    }

    /**
     * Create a payment intent for GCash or PayMaya
     */
    public function createPaymentIntent($amount, $currency = 'PHP', $description = 'Order Payment')
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/payment_intents', [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount * 100, // PayMongo expects amount in centavos
                            'payment_method_allowed' => ['gcash', 'paymaya'],
                            'payment_method_options' => [
                                'card' => [
                                    'request_three_d_secure' => 'automatic'
                                ]
                            ],
                            'currency' => $currency,
                            'capture_type' => 'automatic',
                            'description' => $description
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Payment Intent Creation Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Intent Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Create a GCash source
     */
    public function createGCashSource($amount, $redirectUrl, $description = 'Payment')
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/sources', [
                    'data' => [
                        'attributes' => [
                            'type' => 'gcash',
                            'amount' => $amount * 100, // PayMongo expects amount in centavos
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => $redirectUrl . '?status=success',
                                'failed' => $redirectUrl . '?status=failed'
                            ],
                            'description' => $description
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo GCash Source Creation Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo GCash Source Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Create a PayMaya payment method (using Payment Method API)
     */
    public function createPayMayaPaymentMethod($amount, $redirectUrl, $description = 'Payment')
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/payment_methods', [
                    'data' => [
                        'attributes' => [
                            'type' => 'paymaya',
                            'amount' => $amount * 100, // PayMongo expects amount in centavos
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => $redirectUrl . '?status=success',
                                'failed' => $redirectUrl . '?status=failed'
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo PayMaya Payment Method Creation Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo PayMaya Payment Method Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Attach payment method to payment intent
     */
    public function attachPaymentMethod($paymentIntentId, $paymentMethodId, $returnUrl)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . "/payment_intents/{$paymentIntentId}/attach", [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $paymentMethodId,
                            'return_url' => $returnUrl
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Payment Method Attachment Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Method Attachment Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Retrieve payment intent details
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . "/payment_intents/{$paymentIntentId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Payment Intent Retrieval Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Intent Retrieval Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Retrieve source details
     */
    public function retrieveSource($sourceId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . "/sources/{$sourceId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Source Retrieval Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Source Retrieval Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Create a PayMaya source (similar to GCash)
     */
    public function createPayMayaSource($amount, $redirectUrl, $description = 'Payment')
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/sources', [
                    'data' => [
                        'attributes' => [
                            'type' => 'paymaya',
                            'amount' => $amount * 100, // PayMongo expects amount in centavos
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => $redirectUrl . '?status=success',
                                'failed' => $redirectUrl . '?status=failed'
                            ],
                            'description' => $description
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo PayMaya Source Creation Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo PayMaya Source Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Create a payment (charge) from a chargeable source
     */
    public function createPayment($sourceId, $amount, $description = 'Payment')
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/payments', [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount * 100, // PayMongo expects amount in centavos
                            'currency' => 'PHP',
                            'description' => $description,
                            'source' => [
                                'id' => $sourceId,
                                'type' => 'source'
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Payment Creation Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Creation Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }
}