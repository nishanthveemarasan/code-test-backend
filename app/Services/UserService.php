<?php

namespace App\Services;

use App\Enums\SkillAction;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserService{
    public function store(array $data, User $user){
        $image = $data['image'] ?? null;
        unset($data['image']);
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        if ($image) {
            if ($profile->image) {
                Storage::delete($profile->file->path);
                $profile->file()->delete();
            }
            $path = $image->store('profiles');
            $profile->file()->create([
                'path' => $path,
                'mime_type' => $image->getClientMimeType()
            ]);
        }

    }

    public function storeMainPage(array $data, User $user){
        $mainPage = $user->mainPage()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageData) {
                $action = SkillAction::tryFrom($imageData['action']);
                switch ($action) {
                    case SkillAction::ADD:
                        if (isset($imageData['file'])) {
                            $path = $imageData['file']->store('main_page_images');
                            $mainPage->files()->create([
                                'path' => $path,
                                'mime_type' => $imageData['file']->getClientMimeType(),
                                'title' => $imageData['title'] ?? null,
                                'order' => $imageData['order'] ?? null,
                            ]);
                        }
                        break;
                    case SkillAction::DELETE:
                        if (isset($imageData['uuid'])) {
                            $file = $mainPage->files()->where('uuid', $imageData['uuid'])->first();
                            if ($file) {
                                Storage::delete($file->path);
                                $file->delete();
                            }
                        }
                        break;
                    default:
                        break;
                }
                
                }
            }
        }
}