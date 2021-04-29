<?php


namespace App\Services\Concretes\Payment;


use App\Exceptions\Integration\IntegrationException;
use App\Models\Transaction\Transaction;
use App\Services\Interfaces\Payment\PaymentServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MockPaymentService implements PaymentServiceInterface
{
    private const AUTHORIZATION_URI = '8fafdd68-a090-496f-8c9a-3442cf30dae6';
    
    private Client $client;
    
    public function __construct()
    {
        $this->client = new Client(['base_uri' => env('MOCK_URL')]);
    }
    
    private function request(string $uri, string $method, array $body = null) : array
    {
        try {
            $response = $this->client->request($method, $uri, ['json' => $body]);
            return json_decode($response->getBody(), true);
        } catch (ClientException $exception) {
            throw new IntegrationException(null, $exception);
        }
    }
    
    public function isAuthorized(Transaction $transaction): bool
    {
        $body = [
            'userId' => $transaction->getFromUser()->getId(),
            'amount' => $transaction->getAmount()
        ];
        
        $response = $this->request(self::AUTHORIZATION_URI, 'POST', $body);
        $message = $response['message'] ?? 'Não Autorizado';
        return  $message === 'Autorizado';
    }
}
