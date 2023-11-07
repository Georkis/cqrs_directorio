<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\MailLog;
use App\Domain\Repository\MailLogRepository;

class DoctrineMailLogRepository extends AbstractDoctrineRepository implements MailLogRepository
{
    /**
     * @param MailLog $mailLog
     */
    public function save(MailLog $mailLog): void
    {
        $this->em->persist($mailLog);
    }
}