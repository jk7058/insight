<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Mail\PasswordReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class changePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notifications based on change password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = new Employee();
        $user->where('password_change_status', '0')->whereRaw('DATE_ADD(password_expired_on, INTERVAL -3 DAY) >= CURDATE()')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $mailData = [
                    "user_id" => $user->user_id,
                    "user_name" => $user->name
                ];
                Mail::to($user->userEmail)->send(new PasswordReminderMail($mailData));
            }
        });
    }
}
