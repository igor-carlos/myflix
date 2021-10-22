<?php

namespace Tests\Unit;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;;
use Tests\TestCase; 

class SerieTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_series_list_request_works()
    {
        $response = $this->json('GET', '/api/v1/series');
        $response->assertStatus(200);
    }

    public function test_series_list_is_colletion()
    {
        $response = $this->json('GET', '/api/v1/series');
        $responseData = $response->getOriginalContent();
        $this->assertInstanceOf(Collection::class, $responseData);
    }

    public function test_series_list_loading_works()
    {
        $response = $this->json('GET', '/api/v1/series');
        $responseData = $response->getOriginalContent();
        $this->assertEquals(0, $responseData->count());

        Serie::create(['nome' => 'serie de teste']);

        $response = $this->json('GET', '/api/v1/series');
        $responseData = $response->getOriginalContent();
        $this->assertEquals(1, $responseData->count());
    }

    public function test_serie_create_new_serie()
    {
        $data = [
            'nome' => 'nome-serie-criada', 
            'status' => 'assistido'
        ];
        $response = $this->json('POST', '/api/v1/serie', $data);
        $responseData = $response->getOriginalContent();
        $response->assertStatus(201);
        $this->assertEquals($data['nome'], $responseData['nome']);
        $this->assertEquals($data['status'], $responseData['status']);
    }

    public function test_action_show_response_status_fail()
    {
        $response = $this->json('GET', '/api/v1/serie/1');
        $response->assertStatus(404);
    }

    public function test_action_show_response_status_sucess()
    {
        $data = [
            'nome' => 'nome-serie-criada', 
            'status' => 'assistido'
        ];
        $response = $this->json('POST', '/api/v1/serie', $data);
        $responseData = $response->getOriginalContent();
        $this->assertEquals(1, $responseData['id']);
        $response = $this->json('GET', '/api/v1/serie/1');
        $response->assertStatus(200);
    }
}
