<?php

namespace App\Application\Trait;

use App\Application\Exception\DateNotValidException;
use DateTimeImmutable;

trait DatesParser
{
    /**
     * @param string $dateString
     * @return DateTimeImmutable
     * @throws DateNotValidException
     */
    protected function parseDate(string $dateString): DateTimeImmutable
    {
        if(!$date = DateTimeImmutable::createFromFormat("Y-m-d", $dateString)){
            throw new DateNotValidException();
        }

        return $date;
    }

    /**
     * @param string $dateString
     * @return DateTimeImmutable
     * @throws DateNotValidException
     */
    protected function parseDateTime(string $dateString): DateTimeImmutable
    {
        if(!$date = DateTimeImmutable::createFromFormat(DATE_ISO8601, $dateString)){
            throw new DateNotValidException();
        }

        return $date;
    }
}