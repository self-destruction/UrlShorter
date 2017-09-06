<?php
namespace UrlShorter\Service;

use UrlShorter\Repository\UrlRepository;
use UrlShorter\Model\ShortenUrl;
use UrlShorter\Model\User;


class UrlService
{
    private $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function createShortenUrl(User $user, $longUrl)
    {
        $shortenUrl = $this->urlRepository->saveUserShortenUrl($longUrl, $user);

        return $shortenUrl;
    }

    public function getAllUsersShortenUrls(User $user)
    {
        return $this->urlRepository->getAllUsersShortenUrls($user);
    }

    public function getUsersShortenUrl(User $user, $hash)
    {
        return $this->urlRepository->getUsersShortenUrl($user, $hash);
    }

    public function deleteUsersShortenUrl(User $user, $hash)
    {
        return $this->urlRepository->deleteUsersShortenUrl($user, $hash);
    }
}