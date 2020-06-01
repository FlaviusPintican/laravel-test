<?php declare(strict_types=1);

namespace App\ThirdParty;

use App\Dto\User;
use Illuminate\Support\Facades\Http;

class UserService implements UserServiceInterface
{
    use ClientDomain;

    /**
     * {@inheritDoc}
     */
    public function getUsers(): array
    {
        return $this->mapUsers(Http::get(self::$baseUrl . '/users')->json());
    }

    /**
     * @param array $users
     *
     * @return array
     */
    private function mapUsers(array $users): array
    {
        return array_map(fn (array $user) => (new User($user))->toArray(), $users);
    }
}
