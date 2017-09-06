<?php
namespace UrlShorter\Model;

class Transition
{
    protected $shorten_url;
    protected $date_of_transition;
    protected $count_of_transitions;
    protected $referer;
    protected $count_of_referers;

    public function __construct(
        ShortenUrl $shorten_url, 
        $date_of_transition, 
        $count_of_transitions, 
        $referer, 
        $count_of_referers
    )
    {
        $this->shorten_url = $shorten_url;
        $this->date_of_transition = $date_of_transition;
        $this->count_of_transitions = $count_of_transitions;
        $this->referer = $referer;
        $this->count_of_referers = $count_of_referers;
    }

    public function getShortenUrl()
    {
        return $this->shorten_url;
    }

    public function getDateOfTransition()
    {
        return $this->date_of_transition;
    }

    public function getCountOfTransitions()
    {
        return $this->count_of_transitions;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    public function getCountOfReferers()
    {
        return $this->count_of_referers;
    }
}