<?php

namespace Tests\Unit;

use Tests\TestCase; 

class SerieTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_request_works()
    {
        $response = $this->json('GET', '/api/v1/series');
        $response->assertStatus(200);
    }

    public function test_series_list()
    {
        $response = $this->json('GET', '/api/v1/series');
    }
}
