<?php

namespace App\DataFixtures;

use App\Domain\Entity\Cargo;
use App\Domain\Entity\Role;
use App\Domain\Entity\User;
use App\Domain\Repository\CargoRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Gender;
use App\Domain\ValueObject\UserStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(
        private CargoRepository $cargoRepository,
        private UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $cargos = ["informatico","Contador","Developer","Manager","Especialista"];
        $r = new ArrayCollection();

        foreach ($cargos as $cargo){
            $this->cargoRepository->save(
                Cargo::create(
                    id: Uuid::uuid4(),
                    name: $cargo
                )
            );
        }

        $now = (new \DateTimeImmutable())->setTime(0, 0, 0);

        $user = User::create(
            id: Uuid::uuid4(),
            name: "Georkis",
            birthdate: $now->setDate(year: 1977, month: 5, day: 16),
            status: UserStatus::create(
                status: 'ACTIVE'
            ),
            gender: Gender::create(gender: "MALE"),
            email: "georkis@gmail.com",
            cargos: $r,
        );

        $password = $this->passwordHasher->hashPassword($user, '123456');
        $user->setPassword($password);

        $this->userRepository->save($user);

        $manager->flush();

        //Agregamos los cargos del usuarios
        $especialista = $this->cargoRepository->byName(name:"Especialista");
        $informatico = $this->cargoRepository->byName(name:"informatico");

        $c = new ArrayCollection();
        $c->add(element: $especialista);
        $c->add(element: $informatico);

        $user->setCargos($c);

        $this->userRepository->save($user);
        $manager->flush();

        $user->addCargos([$especialista, $informatico]);
    }

}
