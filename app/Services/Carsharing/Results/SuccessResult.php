<?php

namespace App\Services\Carsharing\Results;

class SuccessResult extends BaseResult
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
