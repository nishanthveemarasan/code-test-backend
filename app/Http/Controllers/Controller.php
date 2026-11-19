<?php

namespace App\Http\Controllers;

use App\Traits\LogExceptionTrait;

abstract class Controller
{
    use LogExceptionTrait;
    public const SUCCESS_MSG = "Operation successful";
    public const ERROR_MSG = "An error occurred! Please try again later.";
}
