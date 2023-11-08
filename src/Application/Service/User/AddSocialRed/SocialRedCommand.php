<?php

namespace App\Application\Service\User\AddSocialRed;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SocialRedCommand
{
    #[Assert\NotBlank]
    private UuidInterface $id;

    #[Assert\NotBlank]
    private array $urls;
    #[Assert\NotBlank]
    private string $userId;

    public function __construct(
        UuidInterface $id,
        array $urls,
        string $userId
    )
    {
        $this->id = $id;
        $this->urls = $urls;
        $this->userId = $userId;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function urls(): array
    {
        return $this->urls;
    }

    public function userId(): string
    {
        return $this->userId;
    }

}