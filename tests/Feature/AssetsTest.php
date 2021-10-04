<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        
    }

    public function testUserCreatesAsset()
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/assets', ['label' => 'testAsset', 'crypto' => 'BTC', 'amount' => 0.5]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'asset created.',
            ]);
    }

    public function testUserCantPostAssetWithoutFillingFields()
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/assets', ['label' => '', 'crypto' => '', 'amount' => '']);

        $response
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors')->has('fields')->etc();
            });
    }

    public function testUserGetsTheirAssets()
    {
        Asset::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/assets');

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) =>
                $json->has('total')
                    ->has('data', 3)
            );
    }

    public function testUserUpdatesAsset()
    {
        $a = Asset::factory()->create(['user_id' => $this->user->id, 'label' => 'test', 'crypto' => 'BTC', 'amount' => 12.3]);

        $this->actingAs($this->user)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->put('/api/assets/' . $a->id, ['label' => 'updated', 'crypto' => 'BTC', 'amount' => 12.3]);

        $updated = Asset::where('id', $a->id)->first();

        $this->assertTrue($updated->label == 'updated');

    }

    public function testUserDeletesAsset()
    {
        $a = Asset::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete('/api/assets/' . $a->id);

        $this->assertDatabaseCount('assets', 0);
    }

    public function testUserCantDeleteSomeoneElseAsset()
    {

        $u1 = User::factory()->create();
        $u2 = User::factory()->create();

        $token = JWTAuth::fromUser($u2);

        $a = Asset::factory()->create(['user_id' => $u1->id]);

        $response = $this->actingAs($u2)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/assets/' . $a->id);

        $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'This action is unauthorized',
            ]);
    }

    public function testUserCantUpdateSomeoneElseAsset()
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();

        $token = JWTAuth::fromUser($u2);

        $a = Asset::factory()->create(['user_id' => $u1->id, 'label' => 'test', 'crypto' => 'BTC', 'amount' => 12.3]);

        $response = $this->actingAs($u2)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/assets/' . $a->id, ['label' => 'updated', 'crypto' => 'BTC', 'amount' => 12.3]);

        $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'This action is unauthorized',
            ]);

    }

}
