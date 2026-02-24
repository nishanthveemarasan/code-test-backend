<?php

namespace Tests\Feature;

use App\Enums\SkillAction;
use App\Models\MainPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MainPageFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_create_a_main_page_with_multiple_images()
    {
        
        $user = User::factory()->create();
        Passport::actingAs($user);

        
        $payload = [
            'title' => 'My Digital Portfolio',
            'description' => 'A collection of my best work.',
            'image' => [
                [
                    'action' => SkillAction::ADD->value,
                    'title'  => 'Project Alpha',
                    'file'   => UploadedFile::fake()->image('alpha.jpg')
                ],
                [
                    'action' => SkillAction::ADD->value,
                    'title'  => 'Project Beta',
                    'file'   => UploadedFile::fake()->image('beta.png')
                ]
            ]
        ];

        
        $response = $this->postJson(route('api.user.main-page-info'), $payload);

       
        $response->assertStatus(200);
        $this->assertDatabaseHas('main_pages', ['title' => 'My Digital Portfolio']);
        
        $mainPage = MainPage::where('user_id', $user->id)->first();
        $this->assertCount(2, $mainPage->files);
        // Storage::disk('public')->assertExists($mainPage->files->first()->path);
    }

    public function test_user_can_delete_an_existing_image_from_main_page()
    {
        
        $user = User::factory()->create();
        Passport::actingAs($user);

        
        $mainPage = MainPage::factory()->create(['user_id' => $user->id]);
        $fileToKeep = $mainPage->files()->create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'path' => 'main_pages/keep.jpg',
            'title' => 'Staying'
        ]);
        $fileToDelete = $mainPage->files()->create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'path' => 'main_pages/delete-me.jpg',
            'title' => 'Going'
        ]);
        
        Storage::disk('public')->put('main_pages/delete-me.jpg', 'fake content');

        
        $payload = [
            'title'   => $mainPage->title,
            'image'   => [
                [
                    'action' => SkillAction::DELETE->value,
                    'uuid'   => $fileToDelete->uuid
                ]
            ]
        ];

        
        $response = $this->postJson(route('api.user.main-page-info'), $payload);

        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('files', ['uuid' => $fileToDelete->uuid]);
        $this->assertDatabaseHas('files', ['uuid' => $fileToKeep->uuid]);
        // Storage::disk('public')->assertMissing('main_pages/delete-me.jpg');
    }

    public function test_unauthorized_user_cannot_modify_other_users_main_page()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $mainPage = MainPage::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($intruder)->postJson('/api/main-page', [
            '_method' => 'PATCH',
            'image_0_action' => 'add',
        ]);

        $response->assertStatus(403);
    }
}
