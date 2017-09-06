<?php
namespace UrlShorter\Repository;

use UrlShorter\Repository\AbstractRepository;
use UrlShorter\Model\Transition;

class TransitionRepository extends AbstractRepository
{
    public function getCountOfTransitions(
        $shortenUrl,
        $from_date,
        $to_date,
        $format)
    {
        switch($format) {
            case 'days':
                $format = '%Y-%m-%d';
                break;
            case 'hours':
                $format = '%Y-%m-%d %H:00';
                break;
            case 'min':
                $format = '%Y-%m-%d %H:%i';
                break;
            default:
                return false;
        }

        if($from_date !== null AND $to_date !== null) {
            $result = $this->dbConnection->fetchAll(
                'SELECT DATE_FORMAT(date_of_transition, ?) AS date_of_transition, COUNT(*) AS count 
                FROM transition WHERE 
                url_hash = ? AND
                DATE(date_of_transition) BETWEEN ? AND ?
                GROUP BY DATE_FORMAT(date_of_transition, ?)',
                [$format, $shortenUrl->getHash(), $from_date, $to_date, $format]
            );
        }
        else
        {
            $result = $this->dbConnection->fetchAll(
                'SELECT DATE_FORMAT(date_of_transition, ?) AS date_of_transition, COUNT(*) AS count 
                FROM transition WHERE 
                url_hash = ? AND
                DATE(date_of_transition) > DATE_SUB(CURDATE(),Interval 7 DAY)
                GROUP BY DATE_FORMAT(date_of_transition, ?)',
                [$format, $shortenUrl->getHash(), $format]
            );
        }

        $rows = [];
        foreach ($result as $row) {
            $rows[] = new Transition($shortenUrl, $row['date_of_transition'], $row['count'], null, null);
        }

        return $rows;
    }

    public function getTopReferers($shortenUrl)
    {
        $result = $this->dbConnection->fetchAll(
            'SELECT referer, COUNT(referer) AS count FROM transition WHERE 
            url_hash = ? AND referer <> ?
            GROUP BY referer ORDER BY count DESC
            LIMIT 20',
            [$shortenUrl->getHash(), 'null']
        );

        $rows = [];
        foreach ($result as $row) {
            $rows[] = new Transition($shortenUrl, null, null, $row['referer'], $row['count']);
            echo $row['count'];
        }

        return $rows;
    }

    public function getLongUrl($hash)
    {
        return $this->dbConnection->fetchArray(
            'SELECT long_url FROM url WHERE hash = ?',
            [$hash]
        );
    }

    public function insertReferer($hash, $referer)
    {
        $result = $this->dbConnection->executeQuery(
            'INSERT INTO transition (url_hash, date_of_transition, referer) VALUES (?, ?, ?)',
            [
              $hash,
              date('Y-m-d H:i:s'),
              $referer
            ]
        );

        return $result->rowCount() === 1 ? true : false;
    }
}
