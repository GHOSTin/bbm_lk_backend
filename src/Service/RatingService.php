<?php


namespace App\Service;


class RatingService extends \App\Service\ApiExternal\AbstractService
{
    const RATING_BY_STUDENT_ROUTE = '/rating/';

    public function getRating($token, $id)
    {
        $this->setToken($token);
        $url = self::RATING_BY_STUDENT_ROUTE .  $id;
        $response = $this->makeGetRequest($url, null, null);
        $rating = $this->getContent($response);
        return json_decode($rating, true);
    }
}