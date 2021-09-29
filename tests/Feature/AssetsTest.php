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

    public function testUserCreatesAsset()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $user->save();

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/assets', ['label' => 'testAsset', 'crypto' => 'BTC', 'amount' => 0.5]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'asset was created.',
            ]);
    }

    public function testUserCantPostAssetWithoutFillingFields()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/assets', ['label' => '', 'crypto' => '', 'amount' => '']);

        $response
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors')->has('fields')->etc();
            });
    }

    public function testUserGetsTheirAssets()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Asset::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
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
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $a = Asset::factory()->create(['user_id' => $user->id, 'label' => 'test', 'crypto' => 'BTC', 'amount' => 12.3]);

        $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/assets/' . $a->id, ['label' => 'updated', 'crypto' => 'BTC', 'amount' => 12.3]);

        $updated = Asset::where('id', $a->id)->first();

        $this->assertTrue($updated->label == 'updated');

    }

    public function testUserDeletesAsset()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $a = Asset::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
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
