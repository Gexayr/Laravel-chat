<?php

namespace App\Jobs;

use App\Emails;
use App\Mail\MessageMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProcessSendingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find(Auth::id());

        $details = [
            'title' => 'Mail from chat room',
            'body' => 'new message - ' . $this->message
        ];

        Mail::to($user->email)->send(new MessageMail($details));

        $data = [
            'user_id' => $user->id,
            'message' => $this->message
        ];
        $email = new Emails($data);
        $email->save();
    }
}
