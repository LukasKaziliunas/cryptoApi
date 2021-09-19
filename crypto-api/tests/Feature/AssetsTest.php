<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_creates_asset()
    {
        $user = User::factory()->create();

        $user->save();

        $response = $this->actingAs($user)->postJson('/api/assets', ['label' => 'testAsset', 'crypto' => 'BTC' , 'amount' => 0.5]);
        
        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'asset created.',
            ]);
    }

    public function test_user_posts_asset_without_filling_fields()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/assets', ['label' => '', 'crypto' => '' , 'amount' => '']);
        
        $response
            ->assertStatus(422)
            ->assertJson(function(AssertableJson $json){
                $json->has('errors')->has('fields')->etc();
            });
    }

    public function test_show_user_assets()
    {
        $user = User::factory()->create();

        Asset::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/assets');

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(3)
            );
    }

    public function test_update_user_asset()
    {
        $user = User::factory()->create();

        $a = Asset::factory()->create(['user_id' => $user->id, 'label' => 'test', 'crypto' => 'BTC', 'amount' => 12.3]);
        
        $this->actingAs($user)->put('/api/assets/' . $a->id, ['label' => 'updated', 'crypto' => 'BTC', 'amount' => 12.3]);

        $updated = Asset::where('id', $a->id)->first();

        $this->assertTrue($updated->label == 'updated');

    }

    public function test_user_asset_deleted()
    {
        $user = User::factory()->create();

        $a = Asset::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete('/api/assets/' . $a->id);

        $this->assertDatabaseCount('assets', 0);
    }

    public function test_user_deleting_someone_else_asset()
    {

        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
  
         $a = Asset::factory()->create(['user_id' => $u1->id]);

         $response = $this->actingAs($u2)->delete('/api/assets/' . $a->id);

         $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'This action is unauthorized',
            ]);
    }
}
