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

class CommentControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_comment(): void
    {
        CarbonImmutable::setTestNow('2025-05-05 13:51:00');

        $admin = Admin::query()->firstOrFail();
        $profile = Profile::query()->firstOrFail();

        $data = ['content' => 'Awesome profile'];

        $response = $this->postJson("api/profile/{$profile->id}/comment", $data);
        $response->assertForbidden();

        $response = $this->actingAs($admin)->postJson("api/profile/{$profile->id}/comment", $data);
        $response->assertConflict();
        $response->assertExactJson([
            'message' => "A comment has already been submitted for the profile {$profile->id} by the admin {$admin->id}",
        ]);

        $profile = Profile::factory()->for($admin)->create();

        $response = $this->actingAs($admin)->postJson("api/profile/{$profile->id}/comment", $data);
        $response->assertCreated();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('data', function (AssertableJson $json) {
                $json->has('id')
                    ->where('content', 'Awesome profile')
                    ->where('created_at', '2025-05-05T13:51:00+02:00')
                    ->where('updated_at', '2025-05-05T13:51:00+02:00');
            });
        });
    }
}
