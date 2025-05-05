<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ProfileStatus;
use App\Models\Admin;
use App\Models\Profile;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_list_profiles(): void
    {
        $admin = Admin::query()->firstOrFail();

        Profile::factory()->for($admin)->create();
        Profile::factory()->for($admin)->create(['status' => 'active']);

        $response = $this->getJson(route('profile.index'));
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonMissingPath('data.0.status');

        $response = $this->actingAs($admin)->getJson(route('profile.index'));
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.status', 'active');
    }

    public function test_create_profile(): void
    {
        Storage::fake();

        CarbonImmutable::setTestNow('2025-05-05 12:51:00');

        $admin = Admin::query()->firstOrFail();

        $data = [
            'lastname' => 'MyLastname',
            'firstname' => 'MyFirstname',
            'image' => UploadedFile::fake()->image('image.jpg'),
            'status' => 'active',
        ];

        $response = $this->postJson(route('profile.store'), $data);
        $response->assertForbidden();

        $response = $this->actingAs($admin)->postJson(route('profile.store'), $data);
        $response->assertCreated();
        $response->assertJson(function (AssertableJson $json) use ($admin) {
            $json->has('data', function (AssertableJson $json) use ($admin) {
                $json->has('id')
                    ->where('lastname', 'MyLastname')
                    ->where('firstname', 'MyFirstname')
                    ->where('admin_id', $admin->id)
                    ->has('image')
                    ->where('status', 'active')
                    ->where('created_at', '2025-05-05T12:51:00+02:00')
                    ->where('updated_at', '2025-05-05T12:51:00+02:00');
            });
        });

        $this->assertInstanceOf(Profile::class, $profile = Profile::find($response->json('data.id')));

        Storage::assertExists($profile->image);
    }

    public function test_update_profile(): void
    {
        Storage::fake();

        CarbonImmutable::setTestNow('2025-05-05 13:03:00');

        $admin = Admin::query()->firstOrFail();
        $profile = Profile::query()->firstOrFail();

        $data = [
            'lastname' => 'MyLastname',
            'firstname' => 'MyFirstname',
            'image' => UploadedFile::fake()->image('image.jpg'),
            'status' => 'pending',
        ];

        $response = $this->putJson(route('profile.update', $profile), $data);
        $response->assertForbidden();

        $response = $this->actingAs($admin)->putJson(route('profile.update', $profile), $data);
        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) use ($admin) {
            $json->has('data', function (AssertableJson $json) use ($admin) {
                $json->has('id')
                    ->where('lastname', 'MyLastname')
                    ->where('firstname', 'MyFirstname')
                    ->where('admin_id', $admin->id)
                    ->has('image')
                    ->where('status', 'pending')
                    ->has('created_at')
                    ->where('updated_at', '2025-05-05T13:03:00+02:00');
            });
        });

        $this->assertInstanceOf(Profile::class, $profile = Profile::find($response->json('data.id')));

        Storage::assertExists($profile->image);
    }

    public function test_delete_profile(): void
    {
        Storage::fake();

        $path = UploadedFile::fake()->image('profile.jpg')->store('uploads');

        $admin = Admin::query()->firstOrFail();
        $profile = Profile::query()->firstOrFail();

        $profile->update(['image' => $path]);

        $response = $this->deleteJson(route('profile.destroy', $profile));
        $response->assertForbidden();

        Storage::assertExists($path);

        $response = $this->actingAs($admin)->deleteJson(route('profile.destroy', $profile));
        $response->assertNoContent();

        Storage::assertMissing($path);

        $this->assertNull(Profile::query()->find($profile->id));
    }
}
