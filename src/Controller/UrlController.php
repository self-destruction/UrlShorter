<?php
namespace UrlShorter\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;

use UrlShorter\Service\UserService;
use UrlShorter\Service\UrlService;
use UrlShorter\Service\TransitionService;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;

class UrlController extends AbstractController
{
    private $urlService;
    private $transitionService;

    public function __construct(
        UserService $userService,
        UrlService $urlService,
        TransitionService $transitionService
    ) {
        parent::__construct($userService);
        $this->urlService = $urlService;
        $this->transitionService = $transitionService;
    }

    public function createShortenUrl(Request $request)
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

            $longUrl = $request->get('url');

            if (filter_var($longUrl, FILTER_VALIDATE_URL)) {

                $shortenUrl = $this->urlService->createShortenUrl(
                    $user,
                    $longUrl
                );

                return new JsonResponse(
                    [
                        'hash' => $shortenUrl->getHash()
                    ],
                    Response::HTTP_CREATED
                );
            } else {
                return $this->createErrorResponse('url is not valid');
            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getAllUsersShortenUrls(Request $request)
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

            $shortenUrls = $this->urlService->getAllUsersShortenUrls($user);

            $arrayOfAllUsersUrls = [];
            foreach ($shortenUrls as $shortenUrl) {
                $arrayOfAllUsersUrls[] = [
                    'hash' => $shortenUrl->getHash(),
                    'url' => $shortenUrl->getLongUrl(),
                    'count_of_views' => $shortenUrl->getCountOfViews(),
                ];
            }

            return new JsonResponse($arrayOfAllUsersUrls);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getUsersShortenUrl(Request $request, $hash)
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

            $shortenUrl = $this->urlService->getUsersShortenUrl($user, $hash);

            if ($shortenUrl === null) {
                return $this->createNoContent();
            }

            return new JsonResponse([
                'hash' => $shortenUrl->getHash(),
                'url' => $shortenUrl->getLongUrl(),
                'count_of_views' => $shortenUrl->getCountOfViews(),
                'average_views_per_day' => $shortenUrl->getAverage(),
                'date_of_urls_creation' => $shortenUrl->getDateOfCreation()
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function deleteUsersShortenUrl(Request $request, $hash)
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

            $rowCount = $this->urlService->deleteUsersShortenUrl($user, $hash);

            if ($rowCount === 0) {
                return $this->createNoContent();
            }
            else {
                return new JsonResponse([
                    'remote hash' => $hash
                ]);

            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getCountOfTransitions(Request $request, $hash, $format)
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

            $shortenUrl = $this->urlService->getUsersShortenUrl($user, $hash);

            if ($shortenUrl === null) {
                return $this->createNoContent();
            }

            $transitions = $this->transitionService->getCountOfTransitions(
                $shortenUrl,
                $_GET['from_date'],
                $_GET['to_date'],
                $format
            );

            if ($transitions === false) {
                return $this->createNoContent();
            }

            $arrayOfTransitions = [];
            foreach ($transitions as $transition) {
                $arrayOfTransitions[] = [
                    $transition->getDateOfTransition() => $transition->getCountOfTransitions()
                ];
            }

            return new JsonResponse($arrayOfTransitions);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getTopReferers(Request $request, $hash)
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

            $shortenUrl = $this->urlService->getUsersShortenUrl($user, $hash);
            
            if ($shortenUrl === null) {
                return $this->createNoContent();
            }


            $referers = $this->transitionService->getTopReferers($shortenUrl);
                $arrayOfReferers = [];
                foreach ($referers as $referer) {
                    $arrayOfReferers[] = [
                        $referer->getReferer() => $referer->getCountOfReferers()
                    ];
                }
                return new JsonResponse($arrayOfReferers);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }
    }

    public function getRedirect(Request $request, $hash)
    {
        $longUrl = $this->transitionService->getLongUrl($hash);

        if ($longUrl === false) {
            return $this->createNoContent();;
        }
        $referer = $request->get('http-referer');  //$_SERVER['HTTP_REFERER']
        $row = $this->transitionService->insertReferer($hash, $referer);

        if($row) {
            return new RedirectResponse($longUrl, 302);
        }
    }
}