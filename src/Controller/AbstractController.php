<?php
namespace UrlShorter\Controller;

use UrlShorter\Model\User;
use UrlShorter\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    protected function getUserByAuthorization($login, $hash)
    {
        $user = $this->userService->getUserByLogin($login);

        if($user !== null && $user->getHash() === $hash) {
            //$user = $this->userService->getCountOfUrls($user);
            return $this->userService->updateAuthenticationTime($user);
        }
        else
            return false;
    }

    protected function authenticationСheck($login, $password)
    {
        //проверка юзера и аутентификация

        if($login === null OR $password === null) {
            return null;
        }
        if($login === '' OR $password === '') {
            return null;
        }

        $user = $this->getUserByAuthorization(
            $login, 
            hash('sha256', $password.'schoolsummerxsolla', false)
        );

        return $user;
        //
    }

    protected function returnAuthenticationError()
    {
        if($login === null OR $password === null) {
            return $this->createUnauthorizedResponse();
        }
        if($login === '' OR $password === '') {
            return $this->createIncorrectLoginOrPassword('login or password can not be empty');
        }
    }

    protected function createUnauthorizedResponse()
    {
        return new JsonResponse(
            [
                'error' => 'not authorized'
            ],
            Response::HTTP_UNAUTHORIZED,
            [
                'WWW-Authenticate' => 'Basic realm="UrlShorter API"'
            ]
        );
    }

    protected function createIncorrectLoginOrPassword($message)
    {
        return new JsonResponse(
            [
                'error' => $message
            ],
            Response::HTTP_UNAUTHORIZED,
            [
                'WWW-Authenticate' => 'Basic realm="UrlShorter API"'
            ]
        );
    }

    protected function createNoContent()
    {
        return new JsonResponse(
            [
                'error' => 'no data'
            ],
            Response::HTTP_NOT_FOUND,
            [
                'WWW-Authenticate' => 'Basic realm="UrlShorter API"'    //что это означает?
            ]
        );
    }

    protected function createErrorResponse($message)
    {
        return new JsonResponse(
            [
                'error' => $message
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

}