<?php
namespace App\Controller\Traits;

use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\Log\Log;

trait PayPalTrait
{
    private $httpClient;
    private $apiEndpoint;

    /**
     * Initialize PayPal-related properties
     */
    public function initializePayPal()
    {
        $this->httpClient = new Client([
            'ssl_verify_peer' => false,  // Temporary workaround
            'ssl_verify_host' => false
        ]);
        $this->apiEndpoint = Configure::read('PayPal.mode') === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
        
        Log::debug('PayPal Config: ' . json_encode([
            'mode' => Configure::read('PayPal.mode'),
            'clientId' => Configure::read('PayPal.clientId'),
            'secret' => Configure::read('PayPal.secret') ? '****' : null
        ]));
    }

    /**
     * Get PayPal access token
     * @return string
     * @throws \Exception
     */
    private function getAccessToken()
    {
        try {
            $clientId = Configure::read('PayPal.clientId');
            $secret = Configure::read('PayPal.secret');

            if (empty($clientId) || empty($secret)) {
                throw new \Exception('PayPal client ID or secret is missing in configuration');
            }

            $authString = base64_encode($clientId . ':' . $secret);
            $authHeader = 'Basic ' . $authString;

            $response = $this->httpClient->post(
                $this->apiEndpoint . '/v1/oauth2/token',
                ['grant_type' => 'client_credentials'],
                [
                    'headers' => [
                        'Authorization' => $authHeader,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ]
                ]
            );

            if ($response->isOk()) {
                $data = $response->json;
                Log::debug('Access Token Response: ' . json_encode($data));
                return $data['access_token'];
            }

            Log::error('Token request failed with status: ' . $response->statusCode() . 
                      ', Body: ' . $response->body());
            throw new \Exception('Failed to get access token: ' . $response->statusCode());

        } catch (\Exception $e) {
            Log::error('Token retrieval failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send money to a PayPal account
     * @param string $recipientEmail
     * @param float $amount
     * @param string $currency
     * @return array
     */
    public function sendMoney($recipientEmail, $amount, $currency = 'USD')
    {
        try {
            if (!$this->validateEmail($recipientEmail)) {
                throw new \Exception('Invalid email address');
            }
            if (!is_numeric($amount) || $amount <= 0) {
                throw new \Exception('Invalid amount');
            }

            $amount = number_format((float)$amount, 2, '.', '');
            $accessToken = $this->getAccessToken();

            $payoutData = [
                'sender_batch_header' => [
                    'sender_batch_id' => uniqid(),
                    'email_subject' => 'Payment from Ritevet App'
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'receiver' => $recipientEmail,
                        'amount' => [
                            'value' => $amount,
                            'currency' => $currency
                        ],
                        'note' => 'Payment via Ritevet',
                        'sender_item_id' => uniqid()
                    ]
                ]
            ];

            $response = $this->httpClient->post(
                $this->apiEndpoint . '/v1/payments/payouts',
                json_encode($payoutData),
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ]
                ]
            );

            if ($response->isOk()) {
                $result = $response->json;
                $batchStatus = $result['batch_header']['batch_status'];

                if (in_array($batchStatus, ['PENDING', 'PROCESSING', 'SUCCESS'])) {
                    $batchId = $result['batch_header']['payout_batch_id'];
                    Log::info("Payment initiated: $batchId for $amount $currency to $recipientEmail");
                    return [
                        'success' => true,
                        'batch_id' => $batchId,
                        'message' => 'Payment initiated successfully'
                    ];
                }

                throw new \Exception('Payout failed with status: ' . $batchStatus);
            }

            Log::error('Payout request failed with status: ' . $response->statusCode() . 
                      ', Body: ' . $response->body());
            throw new \Exception('API request failed: ' . $response->statusCode());

        } catch (\Exception $e) {
            Log::error("Payment error: " . $e->getMessage());
            return [
                'success' => false,
                'batch_id' => null,
                'message' => 'Payment failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate email address
     * @param string $email
     * @return bool
     */
    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 127;
    }
}