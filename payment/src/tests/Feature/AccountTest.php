<?php

namespace Tests\Feature;

use App\Account;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testAccountCanBeInsertedIntoDataBase()
    {
        $this->clearTable();
        $this->createAccount();
        $this->assertCount(1, Account::all());
    }

    public function testAccountCanBeAddedThroughApiRequest()
    {
        $this->clearTable();
        $this->createClient();
        $response = $this->postJson('api/accounts/create', $this->accountData());
        $response->assertStatus(200);
        $response->assertJson(['iban' => true]);
    }

    public function testAccountCanBeFoundThroughApiRequest()
    {
        $this->clearTable();
        $this->createClient();
        $this->postJson('api/accounts/create', $this->accountData());
        $this->getJson('api/accounts/1')->assertStatus(200);
    }

    private function accountData()
    {
        return [
            'clientId' => 1,
            'accountName' => 'testAccount',
            'iban' => 'LT12345678910',
            'balance' => 500,
            'currency' => 'EUR',
        ];
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

    private function clearTable()
    {
        return  $this->artisan('migrate:refresh', [
            '--seed' => true,
        ]);
    }
}
