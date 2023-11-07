<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\MailLog;

interface MailLogRepository extends Repository
{
    public function save(MailLog $mailLog): void;

}