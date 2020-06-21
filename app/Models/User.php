<?php declare(strict_types=1);

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var string[]
     */
    protected $fillable = ['username', 'password', 'first_name', 'family_name', 'email', 'phone_number'];

    /**
     * @var string[]
     */
    protected $hidden = ['password', 'remember_token'];
}
