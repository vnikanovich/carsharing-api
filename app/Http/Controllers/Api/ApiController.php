<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignCarRequest;
use App\Http\Requests\CarUserRequest;
use App\Http\Requests\UnbindCarRequest;
use App\Services\Carsharing\CarsharingService;
use App\Services\Carsharing\Results\BaseResult;

class ApiController extends Controller
{
    private CarsharingService $carsharingService;

    public function __construct(CarsharingService $carsharingService)
    {
        $this->carsharingService = $carsharingService;
    }

    public function assignCar(AssignCarRequest $request)
    {
        $result = $this->carsharingService->assignCar($request->user_id, $request->car_id);

        return $this->getResponse($result);
    }

    public function unbindCar(UnbindCarRequest $request)
    {
        $result = $this->carsharingService->unbindCar($request->user_id);

        return $this->getResponse($result);
    }

    private function getResponse(BaseResult $result)
    {
        if (!$result->isSuccess()) {
            return response()->json(['messages' => $result->getMessage()], 400);
        }

        return response()->json([]);
    }
}
