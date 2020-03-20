<?php

namespace App\Console\Commands;

use App\Notifications\TasksReminderNotification;
use App\User;
use Illuminate\Console\Command;

class SendTasksReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminders to every premium user';

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
        $users = User::whereHas('roles', function ($query) {
                $query->where('id', 3);
            })
            ->whereHas('tasks', function ($query) {
                $query->where('due_date', now()->format('Y-m-d'))
                    ->where('send_reminder', true);
            })
            ->with(['tasks' => function ($query) {
                $query->with('project')
                    ->where('due_date', now()->format('Y-m-d'))
                    ->where('send_reminder', true);
            }])
            ->get();

        foreach ($users as $user) {
            $user->notify(new TasksReminderNotification($user->tasks));
        }

        $this->info($users->count() . ' tasks reminders has been successfully sent');
    }
}
