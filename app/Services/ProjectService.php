<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProjectService{
    public function storeOrUpdate(array $data, User $user, ?Project $project = null){
        $image = $data['image'] ?? null;
        unset($data['image']);
        if ($project) {
            $project->update($data);
        } else {
            $project = $user->projects()->create($data);
        }

        $project->refresh();

        if ($image) {
            if ($project->image) {
                Storage::delete($project->file->path);
                $project->file()->delete();
            }
            $path = $image->store('projects');
            $project->file()->create([
                'path' => $path,
                'mime_type' => $image->getClientMimeType()
            ]);
        }

    }

    public function delete(Project $project): void
    {
        if ($project->file) {
            Storage::delete($project->file->path);
            $project->file()->delete();
        }

        $project->delete();
    }
}