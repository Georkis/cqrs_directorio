<?php declare(strict_types=1);

namespace App\Infrastructure\Annotations;


/**
 * @Annotation
 */
#[\Attribute] class ApiAnnotation
{
    public bool $secure = false;
    private array $groups = [];

    public function __construct(
        bool $secure = false,
        array $groups = []
    )
    {
        $this->secure = $secure;
        $this->groups = $groups;
    }

    public function secure(): bool
    {
        return $this->secure;
    }

    public function groups(): array
    {
        return $this->groups;
    }

}
