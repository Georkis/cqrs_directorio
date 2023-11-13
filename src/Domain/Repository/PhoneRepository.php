<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Phone;

interface PhoneRepository extends Repository
{
    public function byNumber(string $phone): ?Phone;
}