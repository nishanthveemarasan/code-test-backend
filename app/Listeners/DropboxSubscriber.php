<?php

namespace App\Listeners;

use App\Events\AddMainPageImageEvent;
use App\Events\DeleteMainPageImageEvent;
use App\Events\UpdateProfileImageEvent;
use App\Events\UpdateProjectImageEvent;
use App\Exceptions\DropboxUploadException;
use App\Services\DropboxService;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DropboxSubscriber
{
    public $http;

    /**
     * Create the event listener.
     */
    public function __construct(protected DropboxService $dropbboxService)
    {
        $this->http = new Client(['verify' => false]);
    }

    /**
     * Handle the event.
     */
    public function handleUpdateProfileImage(UpdateProfileImageEvent $event): void
    {
        $profile = $event->profile;
        $imageFile = $event->file;
        $folderPath = 'profiles/' . $profile->first_name;
        $this->processDropboxImageUpload($profile, $imageFile, $folderPath);
    }

    public function handleUpdateProjectImage(UpdateProjectImageEvent $event): void
    {
        $project = $event->project;
        $imageFile = $event->file;
        $folderPath = 'projects/';
        $this->processDropboxImageUpload($project, $imageFile, $folderPath);
    }

    public function handleDeleteProfileImage(UpdateProfileImageEvent $event): void
    {
        $profile = $event->profile;
        $folderPath = 'projects/';
        $this->processDropboxImageUpload($profile, null, $folderPath, true);
    }

    public function handleAddMainPageImage(AddMainPageImageEvent $event): void
    {
        $file = $event->file;
        $imageFile = $event->imageFile;
        $folderPath = 'main_page/';
        $this->processDropboxImageUpload($file, $imageFile, $folderPath, false, false);
    }

    public function handleDeleteMainPageImage(DeleteMainPageImageEvent $event): void
    {
        $file = $event->file;
        $folderPath = 'main_page/';
        $this->processDropboxImageUpload($file, null, $folderPath, true, false);
    }

    private function processDropboxImageUpload($model, $imageFile, string $folderPath, bool $deleteAction = false, bool $hasRelation = true): void
    {
        if (!Storage::disk('dropbox')->exists($folderPath)) {
            Storage::disk('dropbox')->makeDirectory($folderPath);
        }

        $path = $hasRelation ? $model->file?->path : $model->path;
        if ($path && Storage::disk('dropbox')->exists($path)) {
            Storage::disk('dropbox')->delete($path);
        }
        if ($deleteAction) {
            if ($hasRelation) {
                $model->file()->delete();
            } else {
                $model->delete();
            }
            return;
        }

 
        $path = Storage::disk('dropbox')->put($folderPath, $imageFile);
        if (!$path) {
            throw DropboxUploadException::uploadFailed("Model ID:{$model->id} Folder Path:{$folderPath}");
        }
        
        if ($hasRelation) {
            $model->file()->updateOrCreate([], [
                'path' => $path,
                'mime_type' => $imageFile->getClientMimeType()
            ]);
        } else {
            $model->update([
                'path' => $path,
            ]);
        }
    }



    /**
     * Method subscribe
     *
     * @param Dispatcher $events [explicite description]
     *
     * @return array
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            UpdateProfileImageEvent::class => 'handleUpdateProfileImage',
            UpdateProjectImageEvent::class => 'handleUpdateProjectImage',
            UpdateProfileImageEvent::class => 'handleDeleteProfileImage',
            AddMainPageImageEvent::class => 'handleAddMainPageImage',
            DeleteMainPageImageEvent::class => 'handleDeleteMainPageImage',
        ];
    }
}
