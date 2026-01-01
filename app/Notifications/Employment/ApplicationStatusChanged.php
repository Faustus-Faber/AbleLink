<?php
// F9 - Evan Yuvraj Munshi

namespace App\Notifications\Employment;

use App\Models\Employment\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    public $application;


    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    /**
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->application->status);
        $jobTitle = $this->application->job->title;
        $employerName = $this->application->job->employer->name;

        return (new MailMessage)
            ->subject("Application Status Update: $status")
            ->line("Your application for the position of **$jobTitle** at **$employerName** has been updated.")
            ->line("New Status: **$status**")
            ->line("Thank you for using our platform!");
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title,
            'status' => $this->application->status,
            'message' => "Your application for {$this->application->job->title} has been marked as {$this->application->status}.",
        ];
    }
}

