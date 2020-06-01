<?php declare(strict_types=1);

namespace App\Commands;

use App\Models\User;
use App\ThirdParty\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from third-party app';

    /**
     * Execute the console command.
     *
     * @param UserService $userService
     *
     * @return void
     */
    public function handle(UserService $userService): void
    {
        $users = $userService->getUsers();
        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $userData) {
            /** @var User $user */
            $user = User::find($userData['id']);

            if (null !== $user && !$user->update($userData)) {
                Log::error(sprintf('User with id %d was not updated!', $user->id));
                continue;
            } elseif (!(new User($userData))->save()) {
                Log::error(sprintf('User with id %d was not saved!', $userData['id']));
                continue;
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
