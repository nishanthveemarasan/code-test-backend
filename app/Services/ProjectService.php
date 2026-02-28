<?php

namespace App\Services;

use App\Events\DeleteProjectImageEvent;
use App\Events\UpdateProjectImageEvent;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProjectService
{

    public function list(User $user)
    {
        $projects = $user->projects()->paginate(10);
        return $projects->toResourceCollection()->response()->getData(true);
    }
    public function storeOrUpdate(array $data, User $user, ?Project $project = null)
    {
        $image = $data['image'] ?? null;
        unset($data['image']);
        if ($project) {
            $project->update($data);
        } else {
            $project = $user->projects()->create($data);
        }

        $project->refresh();

        if ($image) {
            // UpdateProjectImageEvent::dispatch($project, $image);
            if ($project->file) {
                Storage::delete($project->file->path);
                $project->file()->delete();
            }
            $path = $image->store('projects', 'public');
            $project->file()->create([
                'path' => $path,
                'mime_type' => $image->getClientMimeType()
            ]);
        }
        return ['uuid' => $project->uuid];
    }

    public function delete(Project $project): void
    {
        if ($project->file) {
            // DeleteProjectImageEvent::dispatch($project);
            Storage::delete($project->file->path);
            $project->file()->delete();
        }

        $project->delete();
    }
}
