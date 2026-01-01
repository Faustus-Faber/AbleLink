<?php
// F9 - Evan Yuvraj Munshi

namespace App\Notifications\Employment;

use App\Models\Employment\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewScheduled extends Notification
{
    use Queueable;

    public $interview;


    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    /**

     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Interview Scheduled: ' . $this->interview->jobApplication->job->title)
                    ->line('Great news! An interview has been scheduled for your application.')
                    ->line('Job: ' . $this->interview->jobApplication->job->title)
                    ->line('Type: ' . ucfirst($this->interview->type))
                    ->line('Time: ' . $this->interview->scheduled_at->format('M d, Y h:i A'))
                    ->action('View Details', route('candidate.applications')) 
                    ->line('Please prepare accordingly. Good luck!');
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'interview_id' => $this->interview->id,
            'job_id' => $this->interview->jobApplication->job_id,
            'job_title' => $this->interview->jobApplication->job->title,
            'scheduled_at' => $this->interview->scheduled_at,
            'type' => $this->interview->type,
            'message' => "Interview scheduled for {$this->interview->jobApplication->job->title} on {$this->interview->scheduled_at->format('M d, h:i A')}.",
        ];
    }
}

