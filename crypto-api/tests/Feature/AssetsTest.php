<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_creates_asset()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'joe';
        $user->lastname = 'smith';
        $user->email = "joe@example.com";
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

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
        $user = new User();
        $user->id = 1;
        $user->name = 'joe';
        $user->lastname = 'smith';
        $user->email = "joe@example.com";
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        $user->save();

        $response = $this->actingAs($user)->postJson('/api/assets', ['label' => '', 'crypto' => '' , 'amount' => '']);
        
        $response
            ->assertStatus(422)
            ->assertJson(function(AssertableJson $json){
                $json->has('errors')->has('fields')->etc();
            } )
            ;
    }

    public function test_show_user_assets()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'joe';
        $user->lastname = 'smith';
        $user->email = "joe@example.com";
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->save();

        Asset::factory(3)->create(['user_id' => 1]);

        $response = $this->actingAs($user)->getJson('/api/assets');

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(3)
            );
    }

    public function test_update_user_asset()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'joe';
        $user->lastname = 'smith';
        $user->email = "joe@example.com";
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->save();

        $a = Asset::factory()->create(['user_id' => $user->id, 'label' => 'test', 'crypto' => 'BTC', 'amount' => 12.3]);
        
        $this->actingAs($user)->put('/api/assets/' . $a->id, ['label' => 'updated', 'crypto' => 'BTC', 'amount' => 12.3]);

        $updated = Asset::where('id', $a->id)->first();

        $this->assertTrue($updated->label == 'updated');

    }

    public function test_user_asset_deleted()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'joe';
        $user->lastname = 'smith';
        $user->email = "joe@example.com";
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->save();

        $a = Asset::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete('/api/assets/' . $a->id);

        $this->assertDatabaseCount('assets', 0);
    }
}
