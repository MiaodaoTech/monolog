<?php

namespace MdTech\MdLog\Facades;

class MdLog extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mdLog';
    }
}