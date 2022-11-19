<?php

namespace App\Services\Carsharing\Results;

class ErrorResult extends BaseResult
{
    public function __construct(string $message)
    {
        parent::__construct(false, $message);
    }
}
