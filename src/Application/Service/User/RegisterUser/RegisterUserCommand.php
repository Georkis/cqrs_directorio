<?php declare(strict_types=1);

namespace App\Application\Service\User\RegisterUser;

use App\Application\Trait\DatesParser;
use App\Domain\ValueObject\Gender;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserCommand
{
    use DatesParser;

    #[Assert\NotBlank]
    private UuidInterface $id;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    private string $password;

    #[Assert\NotBlank]
    private string $birthdate;

    #[Assert\NotBlank]
    #[Assert\Choice(Gender::VALUES)]
    private string $gender;

    #[Assert\NotBlank]
    private array $cargos;

    private string|null $avatar;

    private array $phones;


    /**
     * @param UuidInterface $id
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $birthdate
     * @param string $gender
     * @param array $cargos
     * @param string $avatar
     * @param array $phones
     */
    public function __construct(
        UuidInterface $id,
        string $email,
        string $name,
        string $password,
        string $birthdate,
        string $gender,
        array $cargos,
        string $avatar,
        array $phones
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->gender = (string)mb_strtoupper($gender);
        $this->cargos = $cargos;
        $this->avatar = $avatar;
        $this->phones = $phones;
    }

    public function gender(): Gender
    {
        return Gender::create($this->gender);
    }

    public function birthdate(): \DateTimeImmutable
    {
        return $this->parseDate($this->birthdate);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function cargos(): array
    {
        $r = [];

        foreach ($this->cargos as $cargo){
            $r[] = Uuid::fromString($cargo);
        }

        return $r;
    }

    public function avatar(): ?string
    {
        return $this->avatar;
    }

    public function phones(): array
    {
        return $this->phones;
    }
}