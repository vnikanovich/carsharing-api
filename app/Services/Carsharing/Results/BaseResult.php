<?php

namespace App\Services\Carsharing\Results;

class BaseResult
{
    private bool $success;
    private ?string $message;

    public function __construct(bool $success, string $message = null)
    {
        $this->success = $success;
        $this->message = $message;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
