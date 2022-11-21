<?php

namespace App\Services\Car;

use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function list(): Collection
    {
        return Car::all();
    }

    public function getById(int $id): ?Car
    {
        return Car::find($id);
    }

    public function isFree(Car $car): bool
    {
        return !$car->users->count();
    }

    public function create(array $data): Car
    {
        return Car::create($data);
    }

    public function update(Car $car, array $data): Car
    {
        $car->update($data);
        $car->refresh();
        return  $car;
    }
}
