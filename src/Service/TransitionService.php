<?php
namespace UrlShorter\Service;

use UrlShorter\Repository\TransitionRepository;

class TransitionService
{
    private $transitionRepository;

    public function __construct(TransitionRepository $transitionRepository)
    {
         $this->transitionRepository = $transitionRepository;
    }

    public function getCountOfTransitions(
        $shortenUrl,
        $from_date,
        $to_date,
        $format)
    {
        return $this->transitionRepository->getCountOfTransitions(
          $shortenUrl,
          $from_date,
          $to_date,
          $format);
    }

    public function getTopReferers($shortenUrl)
    {
        return $this->transitionRepository->getTopReferers($shortenUrl);
    }

    public function getLongUrl($hash)
    {
        return $this->transitionRepository->getLongUrl($hash);
    }
    
    public function insertReferer($hash, $referer)
    {
        return $this->transitionRepository->insertReferer($hash, $referer);
    }
}
