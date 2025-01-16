<?php 

namespace App\Repositories;

use App\Models\User;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\Bridge\UserRepository as PassportUserRepository;

class UserRepository extends PassportUserRepository
{
    public function getUserEntityByUserCredentials($username, $password)
    {
        $user = User::where('email', $username)->first();

        if (!$user || !\Hash::check($password, $user->password)) {
            throw new OAuthServerException('Invalid credentials', 6);
        }

        return $user;
    }
}
