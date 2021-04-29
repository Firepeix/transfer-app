<?php


namespace Tests\Integration\Transaction;


use App\Models\User;
use App\Notifications\Transaction\NewTransactionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;
    
    public function testTransferResponseSuccess(): void
    {
        $payer        = User::factory()->create();
        $payee        = User::factory()->create();
        $payerBalance = $payer->getWallet()->getBalance();
        $payeeBalance = $payee->getWallet()->getBalance();
        $body         = ['value' => 10000, 'payerId' => $payer->getId(), 'payeeId' => $payee->getId()];
        $response     = $this->post('api/transaction', $body);
        $response->assertStatus(200);
        $responseSuccess = [
            'meta' => [
                'message' => 'Transferência feita com sucesso!'
            ],
            'data' => [
                'payerWalletId' => $payer->getWallet()->getId(),
                'payeeWalletId' => $payee->getWallet()->getId(),
                'actionUserId'  => $payer->getId(),
                'payerWallet'   => ['data' => ['balance' => $payerBalance - 10000]],
                'payeeWallet'   => ['data' => ['balance' => $payeeBalance + 10000]],
                'actionUser'    => [
                    'data' => [
                        'name'  => $payer->getFullName(),
                        'email' => $payer->getEmail()
                    ]
                ]
            ]
        ];
        $response->assertJson($responseSuccess);
    }
    
    public function testTransferSuccess(): void
    {
        $payer        = User::factory()->create();
        $payee        = User::factory()->create();
        $payerWallet  = $payer->getWallet();
        $payeeWallet  = $payee->getWallet();
        $payerBalance = $payerWallet->getBalance();
        $payeeBalance = $payeeWallet->getBalance();
        $body         = ['value' => 10000, 'payerId' => $payer->getId(), 'payeeId' => $payee->getId()];
        $response     = $this->post('api/transaction', $body);
        $response->assertStatus(200);
        $newTransaction = [
            'amount'         => 10000,
            'payer_wallet_id' => $payerWallet->getId(),
            'payee_wallet_id'   => $payeeWallet->getId(),
            'user_id'   => $payer->getId()
        ];
        
        $this->assertDatabaseHas('transactions', $newTransaction);
        $this->assertDatabaseHas('wallets', ['id' => $payerWallet->getId(), 'amount' => $payerBalance - 10000]);
        $this->assertDatabaseHas('wallets', ['id' => $payeeWallet->getId(), 'amount' => $payeeBalance + 10000]);
        $newNotification = [
            'type'       => NewTransactionNotification::NEW_TRANSACTION,
            'to_user_id' => $payee->getId(),
            'message'    => "Que bom! Você acabou de receber R$ 100,00 de {$payer->getFullName()}"
        ];
        $this->assertDatabaseHas('notifications', $newNotification);
    }
}
