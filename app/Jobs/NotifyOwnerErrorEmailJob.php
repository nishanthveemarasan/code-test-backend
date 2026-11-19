<?php

namespace App\Jobs;

use App\Mail\SendContactSubmissionErrorMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyOwnerErrorEmailJob implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    public function __construct(public array $data, public string $errorMessage)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(config('admin.owner.email'))->send(new SendContactSubmissionErrorMail($this->data, $this->errorMessage));
    }

    public function failed(\Throwable $e): void
    {
        $title = "Failed to send contact submission error email";
        $error = $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine();
        Log::channel('exception')->error("{$title} :". $error);
    }
}
