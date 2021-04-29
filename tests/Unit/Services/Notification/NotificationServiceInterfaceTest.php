<?php


namespace Services\Notification;


use App\Events\Notification\NewNotification;
use App\Listeners\Notification\SendNotification;
use App\Models\Notification\Notification;
use App\Models\Transaction\Transaction;
use App\Notifications\Transaction\NewTransactionNotification;
use App\Services\Interfaces\Notification\NotificationServiceInterface;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotificationServiceInterfaceTest extends TestCase
{
    public function testDispatchNewTransactionNotification(): void
    {
        Event::fake();
        $service     = app(NotificationServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $notification = new NewTransactionNotification($transaction);
        $service->dispatchNotification($notification);
        Event::assertDispatched(function (NewNotification $event) use ($notification) {
            $eventNotify = $event->getNotification();
            $sameMessage = $eventNotify->getMessage() === $notification->getMessage();
            $sameUser = $eventNotify->getToUser()->getId() === $notification->getToUser()->getId();
            return $sameMessage && $sameUser;
        });
    
        Event::assertListening(NewNotification::class, SendNotification::class);
    }
    
    public function testSendNewTransactionNotification() : void
    {
        $service = app(NotificationServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $notification = new NewTransactionNotification($transaction);
        $isSuccessful = $service->sendNotification($notification);
        $this->assertIsBool($isSuccessful);
    }
    
    public function testCreateNotification() : void
    {
        $service = app(NotificationServiceInterface::class);
        $transaction = Transaction::factory()->make();
        $notify = new NewTransactionNotification($transaction);
        $notification = $service->createNotification($notify);
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertSame($notify->getMessage(), $notification->getMessage());
        $this->assertSame($transaction->getPayee()->getId(), $notification->getToUserId());
        $this->assertSame(NewTransactionNotification::NEW_TRANSACTION, $notification->getType());
    }
}
