<?php
namespace UrlShorter\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    public function register(Request $request)
    {
        try {
            $login = $request->get('login');
            $password = $request->get('password');

            if ($this->userService->getUserByLogin($login) !== null) {
                return $this->createErrorResponse('login already exists');
            }
            if ($login === '' OR $password === '') {
                return $this->createIncorrectLoginOrPassword('login or password can not be empty');
            }
            if (strlen($login) < 6) {
                return $this->createIncorrectLoginOrPassword('login is too short');
            }
            if (strlen($login) > 60) {
                return $this->createIncorrectLoginOrPassword('login is too long');
            }
            if (strlen($password) < 6) {
                return $this->createIncorrectLoginOrPassword('password is too short');
            }
            if (strlen($password) > 60) {
                return $this->createIncorrectLoginOrPassword('password is too long');
            }

            $hash = hash('sha256', $password.'schoolsummerxsolla', false);

            $user = $this->userService->createUser($login, $hash);

            return new JsonResponse(
                [
                    'status' => $user->getLogin().' was created'
                ],
                Response::HTTP_CREATED
            );
        } catch(\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getUser(Request $request)
    {
        try {
            $login = $request->getUser();
            $password = $request->getPassword();

            $user = $this->authenticationСheck(
                $login, 
                $password
            );
            if ($user === null)
                return $this->returnAuthenticationError($login, $password);
            if ($user === false)
                return $this->createIncorrectLoginOrPassword('login or password does not match');

            return new JsonResponse([
                'login' => $user->getLogin(),
                'date_of_registration' => $user->getDateOfRegistration(),
                'date_of_last_authentication' => $user->getLastLogin(),
                'count_of_urls' => $user->getCountOfUrls()
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }
}
