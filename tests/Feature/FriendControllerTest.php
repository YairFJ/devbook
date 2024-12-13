<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Deshabilitar la verificación de CSRF para pruebas
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    public function test_user_can_send_friend_request()
    {
        // Crea el usuario que enviará la solicitud
        $user = User::factory()->create();

        // Crea el usuario que recibirá la solicitud
        $anotherUser = User::factory()->create();

        // Autentica al usuario que enviará la solicitud
        $this->actingAs($user);

        // Envía la solicitud de amistad
        $response = $this->post('/send-friend-request/' . $anotherUser->id);

        // Verifica que la respuesta sea la esperada
        $response->assertStatus(201); // Ahora debería pasar si todo está correcto
    }
}