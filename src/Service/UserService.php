<?php
namespace UrlShorter\Service;

use UrlShorter\Model\User;
use UrlShorter\Repository\UserRepository;

class UserService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser($login, $hash)
    {
        $user = new User($login, $hash, null, null, 0);

        return $user = $this->userRepository->saveUser($user);
    }

    public function getUserByLogin($login)
    {
        $user = new User($login, null, null, null, 0);
        return $this->userRepository->getUserByLogin($user);
    }

    /*public function getCountOfUrls(User $user)
    {
        return $this->userRepository->getCountOfUrls($user);
    }*/

    public function updateAuthenticationTime(User $user)
    {
        return $this->userRepository->updateAuthenticationTime($user);
    }
}