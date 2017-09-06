<?php
namespace UrlShorter\Repository;

use UrlShorter\Model\User;

class UserRepository extends AbstractRepository
{

    public function getUserByLogin($user)
    {
        $userRow = $this->dbConnection->fetchArray(
            'SELECT hash, date_of_registration, last_login, count_of_urls FROM user WHERE login = ?', [$user->getLogin()]
        );

        return $userRow[0] !== null 
        ? new User(
            $user->getLogin(),
            $userRow[0], 
            $userRow[1], 
            $userRow[2], 
            $userRow[3]
            ) 
        : null;
    }

    public function saveUser(User $user)
    {
        $now = date("Y-m-d H:i:s");
        $this->dbConnection->executeQuery(
            'INSERT INTO user (login, hash, date_of_registration, last_login) VALUES (?, ?, ?, ?)',
            [
                $user->getLogin(), 
                $user->getHash(), 
                $now,
                $now
            ]
        );

        $user = new User(
            $user->getLogin(),
            $user->getHash(),
            $now,
            $now,
            $user->getCountOfUrls()
        );

        return $user;
    }


    public function updateAuthenticationTime($user)
    {
        $userRow = $this->dbConnection->fetchArray(
            'SELECT last_login FROM user WHERE login = ?', 
            [$user->getLogin()]
        );

        $now = date("Y-m-d H:i:s").'';
        $this->dbConnection->executeQuery(
            'UPDATE user SET last_login = ? WHERE login = ?',
            [$now, $user->getLogin()]
        );

        return new User(
            $user->getLogin(),
            $user->getHash(),
            $user->getDateOfRegistration(),
            $userRow[0],
            $user->getCountOfUrls()
        );
    }
}
