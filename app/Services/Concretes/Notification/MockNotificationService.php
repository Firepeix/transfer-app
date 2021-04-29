<?php


namespace App\Services\Concretes\Notification;

use App\Events\Interfaces\Notification\NotifyInterface;
use App\Events\Notification\NewNotification;
use App\Exceptions\Integration\IntegrationException;
use App\Models\Notification\Notification;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MockNotificationService implements NotificationServiceInterface
{
    private const SEND_URI = 'b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
    
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
        } catch (GuzzleException $exception) {
            throw new IntegrationException(null, $exception);
        }
    }
    
    public function dispatchNotification(NotifyInterface $notify): void
    {
        event(new NewNotification($notify));
    }
    
    public function sendNotification(NotifyInterface $notify): bool
    {
        $body = [
            'userId' => $notify->getToUser()->getId(),
            'message' => $notify->getMessage()
        ];
    
        $response = $this->request(self::SEND_URI, 'POST', $body);
        $message = $response['message'] ?? 'Não Enviado';
        return  $message === 'Enviado';
    }
    
    public function createNotification(NotifyInterface $notify): Notification
    {
        $notification = new Notification();
        $notification->setMessage($notify->getMessage());
        $notification->setType($notify->getType());
        $notification->setToUser($notify->getToUser());
        return $notification;
    }
}
