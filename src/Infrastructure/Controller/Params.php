<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

class Params
{
    /**
     * @var mixed[]
     */
    private array $params;

    /**
     * Params constructor.
     * @param mixed[] $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function get(string $name): mixed
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } elseif (str_contains($name, '.')) {
            $loc = &$this->params;
            foreach (explode('.', $name) as $part) {
                $loc = &$loc[$part];
            }
            return $loc ?? null;
        }
        return null;
    }

    /**
     * @return mixed[]
     */
    public function getAll(): array
    {
        return $this->params;
    }
}
