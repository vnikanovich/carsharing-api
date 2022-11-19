<?php

namespace App\Services\Carsharing;

use App\Services\Car\CarService;
use App\Services\Carsharing\Results\BaseResult;
use App\Services\Carsharing\Results\ErrorResult;
use App\Services\Carsharing\Results\SuccessResult;
use App\Services\User\UserService;

class CarsharingService
{
    private UserService $userService;
    private CarService $carService;

    public function __construct(UserService $userService, CarService $carService)
    {
        $this->userService = $userService;
        $this->carService = $carService;
    }

    public function assignCar(int $userId, int $carId): BaseResult
    {
        $user = $this->userService->getById($userId);

        if ($this->userService->hasCar($user)) {
            return new ErrorResult('USER_ALREADY_HAS_CAR');
        }

        $car = $this->carService->getById($carId);

        if (!$this->carService->isFree($car)) {
            return new ErrorResult('CAR_IS_NOT_FREE');
        }

        $user->cars()->attach($carId);

        return new SuccessResult();
    }

    public function unbindCar(int $userId): BaseResult
    {
        $user = $this->userService->getById($userId);

        if (!$this->userService->hasCar($user)) {
            return new ErrorResult('USER_DOES_NOT_HAVE_CAR');
        }

        $user->cars()->detach();

        return new SuccessResult();
    }
}
