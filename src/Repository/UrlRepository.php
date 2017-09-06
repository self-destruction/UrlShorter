<?php
namespace UrlShorter\Repository;

use UrlShorter\Repository\AbstractRepository;
use UrlShorter\Model\ShortenUrl;
use UrlShorter\Model\User;

class UrlRepository extends AbstractRepository
{
    public function saveUserShortenUrl($longUrl, User $user)
    {
        $result = null;
        do {
            $array = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
            $hash = '';
            for ($i=0; $i<12; $i=$i+1) {
                $hash .= $array[rand(0, 61)];
            }
            $result = $this->dbConnection->fetchAll(
                'SELECT hash FROM url 
                 WHERE hash=?',
                [$hash]
            );
        } while($result === null);


        $now = date("Y-m-d H:i:s");
        $this->dbConnection->executeQuery(
            'INSERT INTO url (hash, long_url, user_login, count_of_view, date_of_creation) VALUES(?, ?, ?, 0, ?)',
            [
                $hash,
                $longUrl,
                $user->getLogin(),
                $now
            ]
        );

        $shortenUrl = new ShortenUrl(
            $hash,
            $longUrl,
            $user,
            0,
            $now,
            0
        );

        return $shortenUrl;
    }

    public function getAllUsersShortenUrls(User $user)
    {
        $result = $this->dbConnection->fetchAll(
            'SELECT hash, long_url, count_of_view, date_of_creation FROM url 
             WHERE user_login=?
             ORDER BY date_of_creation DESC',
            [$user->getLogin()]
        );
        //return $result;

        $shortenUrls = [];
        foreach ($result as $row) {
            $shortenUrls[] = new ShortenUrl(
                $row['hash'], 
                $row['long_url'], 
                $user, 
                $row['count_of_view'],
                $row['date_of_creation'],
                0
            );
        }

        return $shortenUrls;
    }

    public function getUsersShortenUrl(User $user, $hash)
    {
        $row = $this->dbConnection->fetchArray(
            'SELECT long_url, count_of_view, date_of_creation, 
            ROUND( COUNT(*)  / (DATEDIFF(MAX(date_of_transition),MIN(date_of_transition)) + 1), 1 ) AS average FROM url 
            INNER JOIN transition ON
            hash = ? AND url_hash = ? AND user_login = ? ',
            [$hash, $hash, $user->getLogin()]
        );

        //?return $row !== null - почему-то не работает
        return $row[0] !== null ? new ShortenUrl($hash, $row[0], $user, $row[1], $row[2], $row[3] === null ? '0' : $row[3]) : null;
    }

    public function deleteUsersShortenUrl(User $user, $hash)
    {
        $result = $this->dbConnection->executeQuery(
            'DELETE FROM url WHERE hash = ? AND user_login = ?',
            [$hash, $user->getLogin()]
        );

        return $result->rowCount();
    }
}
