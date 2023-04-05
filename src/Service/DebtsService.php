<?php


namespace App\Service;


use App\Helper\Mapped\Debt;

class DebtsService extends \App\Service\ApiExternal\AbstractService
{
    private const DEBT_BY_STUDENT_ROUTE = '/debt';

    public function getDebts($token)
    {
        $this->setToken($token);
        $url = self::DEBT_BY_STUDENT_ROUTE;
        $response = $this->makeGetRequest($url, null, null);
        $data = $this->getContent($response);
        return $this->serializer->deserialize($data, Debt::class, "json");
    }
}