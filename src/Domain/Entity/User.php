<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Event\User\UserCreated;
use App\Domain\Entity\Event\User\UserUpdated;
use App\Domain\ValueObject\Gender;
use App\Domain\ValueObject\UserStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class User extends AbstractUuidEntity implements PasswordAuthenticatedUserInterface
{
    protected ?string $createdClassEvent = UserCreated::class;
    protected ?string $updatedClassEvent = UserUpdated::class;

    private string $email;
    private string $name;
    private Gender $gender;
    private string $password;
    private \DateTimeImmutable $birthdate;

    private ?Collection $cargos;
    private UserStatus $status;

    private ?string $avatar;

    private Collection $socialReds;

    private Collection $phones;

    /**
     * @return static
     */
    public static function create(
        UuidInterface $id,
        string $email,
        string $name,
        UserStatus $status,
        Gender $gender,
        \DateTimeImmutable $birthdate,
        Collection $cargos,
        ?string $avatar,
    ): self
    {
        $e = new static($id);
        $e->email = $email;
        $e->name = $name;
        $e->birthdate = $birthdate;
        $e->status = $status;
        $e->gender = $gender;
        $e->password = "";
        $e->cargos = new ArrayCollection();
        $e->setCargos($cargos);
        $e->avatar = ! empty($avatar) ? $avatar : null;

        return $e;
    }

    public function setCargos(Collection $cargos): void
    {
        $this->cargos = $cargos;
    }

    public function setPhones(array $phones): void
    {
        $p = new ArrayCollection();

        foreach ($phones as $phone){
            $p->add(
                Phone::create(
                    id: Uuid::uuid4(),
                    prefixNumber: $phone['prefixNumber'],
                    number: $phone['number'],
                    user: $this,
                    name: $phone['name']
                )
            );
        }

        $this->phones = $p;
    }

    public function addSocialReds(
        UuidInterface $id,
        string $url
    )
    {
        //todo valid if user has the same url

        $this->socialReds->add(
            SocialRed::create(
                id: $id,
                url: $url,
                user: $this
            )
        );
    }

    public function getSocialReds()
    {
        return $this->socialReds;
    }

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function birthdate(): \DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function cargos(): Collection
    {
        return  $this->cargos;
    }

    public function gender(): Gender
    {
        return $this->gender;
    }

    public function addCargos(array $cargos)
    {
        //todo dev
        foreach ($cargos as $cargo){
            $this->cargos->add($cargo);
        }

        $this->cargos;
    }

    public function avatar(): string
    {
        return $this->avatar;
    }

    public function phones()
    {
        return $this->phones;
    }
}
