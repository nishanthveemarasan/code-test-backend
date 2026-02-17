<?php

namespace App\Listeners;

use App\Events\NotifyOwnerEvent;
use App\Events\NotifyUserEvent;
use App\Mail\OwnerSendQueryToOwerEmail;
use App\Mail\SendNotifyUserEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactUsSubscriber implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

  
    /**
     * Method handleNotifyUser
     *
     * @param NotifyUserEvent $event [explicite description]
     *
     * @return void
     */
    public function handleNotifyUser(NotifyUserEvent $event): void
    {
        Mail::to($event->contactUs->email)->send(new SendNotifyUserEmail($event->contactUs));
    }
    
    /**
     * Method handleNotifyOwner
     *
     * @param NotifyOwnerEvent $event [explicite description]
     *
     * @return void
     */
    public function handleNotifyOwner(NotifyOwnerEvent $event): void
    {
        Mail::to('test@gmai.com')->send(new OwnerSendQueryToOwerEmail($event->contactUs));
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
            NotifyUserEvent::class => 'handleNotifyUser',
            NotifyOwnerEvent::class => 'handleNotifyOwner',
        ];
    }

    /**
     * Handle a job failure for any event in this subscriber.
     */
    public function failed(mixed $event, \Throwable $exception): void
    {
        // Determine which event failed to log the right info
        if ($event instanceof NotifyUserEvent) {
            Log::channel('queueException')->error('Auto-Response to User Failed', [
                'user_email' => $event->contactUs->email,
                'error' => $exception->getMessage()
            ]);
        } 
        
        if ($event instanceof NotifyOwnerEvent) {
            Log::channel('queueException')->error('Notification to Owner Failed', [
                'query_id' => $event->contactUs->id,
                'error' => $exception->getMessage()
            ]);
        }
    }

    

    


}
