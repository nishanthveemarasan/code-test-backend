<?php

namespace App\Services;

use App\Enums\SkillAction;
use App\Events\AddMainPageImageEvent;
use App\Events\DeleteMainPageImageEvent;
use App\Events\UpdateProfileImageEvent;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function store(array $data, User $user)
    {
        $image = $data['image'] ?? null;
        unset($data['image']);
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        if ($image) {
           UpdateProfileImageEvent::dispatch($profile, $image);
            // if ($profile->image) {
            //     Storage::delete($profile->file->path);
            //     $profile->file()->delete();
            // }
            // $path = $image->store('profiles', 'public');
            // $profile->file()->create([
            //     'path' => $path,
            //     'mime_type' => $image->getClientMimeType()
            // ]);
        }
        return ['uuid' => $profile->uuid];
    }

    public function storeMainPage(array $data, User $user)
    {
        $mainPage = $user->mainPage()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );

        // if (isset($data['images'])) {
            foreach ($data['images'] as $imageData) {
                $action = SkillAction::tryFrom($imageData['action']);
                switch ($action) {
                    case SkillAction::ADD:
                        if (isset($imageData['file'])) {
                            $file = $mainPage->files()->create([
                                'path' => '',
                                'mime_type' => $imageData['file']->getClientMimeType(),
                                'title' => $imageData['title'] ?? null,
                                'order' => $imageData['order'] ?? null,
                            ]);
                            AddMainPageImageEvent::dispatch($file, $imageData['file']);
                        }
                        break;
                    case SkillAction::UPDATE:
                        if (isset($imageData['uuid'])) {
                            $file = $mainPage->files()->where('uuid', $imageData['uuid'])->first();
                            if ($file) {
                                $file->update([
                                    'title' => $imageData['title'] ?? $file->title,
                                    'order' => $imageData['order'] ?? $file->order,
                                ]);
                            }
                        }
                        break;
                    case SkillAction::DELETE:
                        if (isset($imageData['uuid'])) {
                            $file = $mainPage->files()->where('uuid', $imageData['uuid'])->first();
                            if ($file) {
                                DeleteMainPageImageEvent::dispatch($file);
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        // }
    }

    public function getData(User $user)
    {
        $mainPage = $user->mainPage()->with(['files' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->first();
        return $mainPage ? $mainPage->toResource() : null;
    }

    public function getProfileData(User $user)
    {
        $profile = $user->profile()->first();
        return $profile ? $profile->toResource() : null;
    }
}
