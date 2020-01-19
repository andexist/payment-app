<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Client;

/**
 * Class ClientTest
 * @package Tests\Feature
 */
class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testClientCanBeInsertedIntoDataBase()
    {
        $this->clearTable();
        $this->createClient();
        $this->assertCount(1, Client::all());
    }

    public function testClientCanBeAddedThroughApiRequest()
    {
        $response = $this->postJson('api/clients/create', $this->clientData());
        $response->assertStatus(200);
        $response->assertJson(['createdAt' => true]);
    }

    public function testClientCanBeFoundThroughApiRequest()
    {
        $this->artisan('migrate:refresh', [
            '--seed' => true,
        ]);

        $this->postJson('api/clients/create', $this->clientData());
        $this->getJson('api/clients/1')->assertStatus(200);
    }

    private function clientData()
    {
        return [
            'username' => 'testJsonUsername',
            'firstName' => 'testJsonName',
            'lastName' => 'testJsonLastName',
        ];
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
        return $this->artisan('migrate:refresh', [
            '--seed' => true,
        ]);
    }
}
