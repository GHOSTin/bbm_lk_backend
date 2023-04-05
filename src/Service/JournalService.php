<?php


namespace App\Service;


use App\Filter\JournalFilter;
use App\Helper\Mapped\Journal;

class JournalService extends \App\Service\ApiExternal\AbstractService
{
    const STUDENT_JOURNALS = '/journals/';

    public function getJournal($studentId, JournalFilter $filter, $token)
    {
        $startDateTimestamp = DateTimeService::getDateTimeFromString($filter->getDateStart())
            ->modify('midnight')
            ->getTimestamp();
        $this->setToken($token);
        $url = self::STUDENT_JOURNALS . "/" .$studentId . "/" . $startDateTimestamp . "/" . $startDateTimestamp;
        $journalByDate = $this->getContent($this->makeGetRequest($url, null, null));
        $journals = $this->serializer->deserialize($journalByDate, Journal::class, "json");
        return $journals;
    }
}
