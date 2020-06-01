<?php declare(strict_types=1);

namespace App\Console;

use App\Commands\ImportComments;
use App\Commands\ImportPosts;
use App\Commands\ImportUsers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var string[]
     */
    protected $commands = [
        ImportUsers::class,
        ImportPosts::class,
        ImportComments::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:import-users')
            ->everyTenMinutes()
            ->appendOutputTo('/storage/logs/import-users.log');

        $schedule->command('app:import-posts')
                 ->everyTenMinutes()
                 ->appendOutputTo('/storage/logs/import-posts.log');

        $schedule->command('app:import-comments')
                 ->everyTenMinutes()
                 ->appendOutputTo('/storage/logs/import-comments.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
