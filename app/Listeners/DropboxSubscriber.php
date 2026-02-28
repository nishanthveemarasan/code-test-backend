<?php

namespace App\Listeners;

use App\Events\UpdateProfileImageEvent;
use App\Services\DropboxService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

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
        $this->dropbboxService->connectDropbox('store_images');
        if ($profile->file?->path) {
            Storage::disk('dropbox')->delete($profile->file->path);
        }
        $folderPath = 'profiles/' . $profile->first_name;
        if (!Storage::disk('dropbox')->exists($folderPath)) {
            Storage::disk('dropbox')->makeDirectory($folderPath);
        }
        $path = Storage::disk('dropbox')->put($folderPath, $imageFile);
        if ($profile->file) {
            $profile->file()->update([
                'path' => $path,
                'mime_type' => $imageFile->getClientMimeType()
            ]);
        } else {
            $profile->file()->create([
                'path' => $path,
                'mime_type' => $imageFile->getClientMimeType()
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
        ];
    }
}
