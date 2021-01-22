<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    /**
     * Get all subscribers
     *
     * @return void
     */
    public function test_get_subscribers_response()
    {
        $response = $this->get(route('members.index'));
        $response->assertStatus(200);
    }

    /**
     * Store a subscriber
     *
     * @return void
     */
    public function test_store_subscribers_response()
    {
        $response = $this->post(route('members.store'), [
            'Name' => 'Andreas Symeou',
            'EmailAddress' => 'asymeou@gmail.com'
        ]);
        $response->assertStatus(201);
    }

    /**
     * Delete a subscriber
     *
     * @return void
     */
    public function test_delete_subscribers_response()
    {
        $response = $this->delete(route('members.delete'), [
            'EmailAddress' => 'asymeou@gmail.com'
        ]);
        $response->assertStatus(200);
    }
}
