<?php

namespace Tests\Feature;

use App\Account;
use App\Client;
use App\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testPaymentCanBeInsertedIntoDataBase()
    {
        $this->clearTable();
        $this->createPayment();
        $this->assertCount(1, Payment::all());
    }

    public function testAccountCanBeAddedThroughApiRequest()
    {
        $this->clearTable();
        $this->createClient();
        $this->createAccount();
        $response = $this->postJson('api/payments/create', $this->paymentData());
        $response->assertStatus(200);
        $response->assertJson(['receiver_account' => true]);
    }

    public function testPaymentCanBeApprovedThroughApiRequest()
    {
        $this->clearTable();
        $this->createPayment();
        $response = $this->postJson('api/payments/approve', [
            'paymentId' => 1,
            'code' => 111
        ]);
        $response->assertStatus(200);
        $response->assertJson(['receiver_account' => true]);
    }

    public function testPaymentCanBeRejectedThroughApiRequest()
    {
        $this->clearTable();
        $this->createPayment();
        $response = $this->postJson('api/payments/reject', [
            'paymentId' => 1,
        ]);
        $response->assertStatus(200);
        $response->assertJson(['receiver_account' => true]);
    }

    private function clearTable()
    {
        return $this->artisan('migrate:refresh', [
            '--seed' => true,
        ]);
    }

    private function paymentData()
    {
        return [
            "accountId" => 1,
            "currency" => "USD",
            "amount" => 135.12,
            "receiverAccount" => "LT15104499874",
            "receiverName" => "Test person",
            "details" => "some test details",
        ];
    }

    private function createPayment()
    {
        return factory(Payment::class)->create([
            "account_id" => 1,
            "currency" => "USD",
            "amount" => 135.12,
            "receiver_account" => "LT15104499874",
            "receiver_name" => "Test person",
            "details" => "some test details",
        ]);
    }

    private function createAccount()
    {
        return factory(Account::class)->create([
            'client_id' => 1,
            'account_name' => 'testAccount',
            'iban' => 'LT123456789',
            'balance' => 0,
            'currency' => 'EUR',
        ]);
    }

    private function createClient()
    {
        return factory(Client::class)->create([
            'username' => 'testClient',
            'first_name' => 'testName',
            'last_name' => 'testLastName',
        ]);
    }
}
