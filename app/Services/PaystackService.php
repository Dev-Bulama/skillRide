<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key', '');
        $this->baseUrl = config('services.paystack.payment_url', 'https://api.paystack.co');
    }

    public function initializeTransaction(string $email, float $amount, string $reference, ?string $callbackUrl = null): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/transaction/initialize', [
                    'email' => $email,
                    'amount' => (int) ($amount * 100),
                    'reference' => $reference,
                    'callback_url' => $callbackUrl,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack initialize failed: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get($this->baseUrl . '/transaction/verify/' . $reference);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack verify failed: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function listBanks(): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get($this->baseUrl . '/bank');

            return $response->json()['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Paystack list banks failed: ' . $e->getMessage());
            return [];
        }
    }

    public function createTransferRecipient(string $name, string $accountNumber, string $bankCode): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/transferrecipient', [
                    'type' => 'nuban',
                    'name' => $name,
                    'account_number' => $accountNumber,
                    'bank_code' => $bankCode,
                    'currency' => 'NGN',
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack create recipient failed: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function initiateTransfer(float $amount, string $recipientCode, string $reason): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/transfer', [
                    'source' => 'balance',
                    'amount' => (int) ($amount * 100),
                    'recipient' => $recipientCode,
                    'reason' => $reason,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack transfer failed: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
