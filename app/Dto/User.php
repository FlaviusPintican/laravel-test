<?php declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;

class User
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $website;

    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        if (!isset(
            $user['id'],
            $user['name'],
            $user['username'],
            $user['email'],
            $user['phone'],
            $user['website']
        )) {
            throw new InvalidArgumentException(
                'The following keys are missing ' .
                implode(',', array_diff(['name', 'username', 'email', 'phone', 'website'], array_keys($user)))
            );
        }

        $this->id = $user['id'];
        $this->name = $user['name'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->phone = $user['phone'];
        $this->website = $user['website'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
        ];
    }
}
