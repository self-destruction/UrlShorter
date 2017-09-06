<?php
namespace UrlShorter\Model;

class User
{
    //private $id;
    private $login;
    private $hash;
    private $date_of_registration;
    private $last_login;
    private $count_of_urls;

    public function __construct($login, $hash, $date_of_registration, $last_login, $count_of_urls)
    {
    	

        //$this->id = $id;
        $this->login = $login;
        $this->hash = $hash;
        $this->date_of_registration = $date_of_registration;
        $this->last_login = $last_login;
        $this->count_of_urls = $count_of_urls;
    }

    /*public function getId()
    {
        return $this->id;
    }*/

    public function getLogin()
    {
        return $this->login;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getDateOfRegistration()
    {
        return $this->date_of_registration;
    }

    public function getLastLogin()
    {
        return $this->last_login;
    }

    public function getCountOfUrls()
    {
        return $this->count_of_urls;
    }
}
