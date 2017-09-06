<?php
namespace UrlShorter\Model;

use UrlShorter\Model\User;

class ShortenUrl
{
    protected $hash;
    protected $long_url;
    protected $user;
    protected $count_of_views;
    protected $date_of_creation;
    protected $average;

    public function __construct($hash, $long_url, User $user, $count_of_views, $date_of_creation, $average)
    {
        $this->hash = $hash;
        $this->long_url = $long_url;
        $this->user = $user;
        $this->count_of_views = $count_of_views;
        $this->date_of_creation = $date_of_creation;
        $this->average = $average;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getLongUrl()
    {
        return $this->long_url;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCountOfViews()
    {
        return $this->count_of_views;
    }

    public function getDateOfCreation()
    {
        return $this->date_of_creation;
    }

    public function getAverage()
    {
        return $this->average;
    }
}