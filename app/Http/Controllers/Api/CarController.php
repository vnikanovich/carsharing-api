<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\Car\CarService;

class CarController extends Controller
{
    private CarService $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function index()
    {
        return response()->json(CarResource::collection($this->carService->list()));
    }

    public function show(Car $car)
    {
        return response()->json(CarResource::make($car));
    }

    public function store(CarRequest $request)
    {
        $data = $request->validated();
        $car = $this->carService->create($data);

        return response()->json(CarResource::make($car));
    }

    public function update(Car $car, CarRequest $request)
    {
        $data = $request->validated();
        $car = $this->carService->update($car, $data);

        return response()->json(CarResource::make($car));
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return response()->json([]);
    }
}
