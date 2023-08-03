<?php

namespace MdTech\MdLog\Exceptions;

class LoggerInvalidException extends \Exception
{
    protected $code = 500;

    protected $message = 'Invalid Log Type';
}