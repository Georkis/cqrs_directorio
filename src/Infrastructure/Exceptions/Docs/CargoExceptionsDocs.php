<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;

use App\Application\Exception\CargoIsExistException;
use App\Application\Exception\CargoNotExistException;
use App\Application\Exception\PetBreedNotExistException;
use App\Application\Exception\PetNotExistException;
use App\Application\Exception\PetNotValidException;
use App\Application\Exception\PetPersonalityNotExistException;
use App\Application\Exception\PetPersonalityTypeNotValidException;
use App\Domain\Exception\PetAlreadyHasEatingHabitException;
use App\Domain\Exception\PetAlreadyHasMedicalConditionException;
use App\Domain\Exception\PetAlreadyHasPersonalityException;
use App\Domain\Exception\PetBreedNotValidException;
use App\Domain\Exception\PetEatingHabitNotValidException;
use App\Domain\Exception\PetEatingHabitTypeNotValidException;
use App\Domain\Exception\PetMedicalConditionTypeNotValidException;
use Symfony\Component\HttpFoundation\Response;

class CargoExceptionsDocs extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            CargoIsExistException::class,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            "Cargo is exist"
        );

        $this->addError(
            classname: CargoNotExistException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Cargo is not valid"
        );
    }

    protected function baseError(): string
    {
        return "PET";
    }
}
