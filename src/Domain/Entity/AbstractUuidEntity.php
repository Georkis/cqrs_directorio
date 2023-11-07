<?php declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

abstract class AbstractUuidEntity extends AbstractEntity
{

    /** @var UuidInterface */
    protected UuidInterface $id;

    protected function __construct(UuidInterface $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
}
