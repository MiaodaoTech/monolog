<?php

namespace MdTech\MdLog\Traits;

use Illuminate\Support\Facades\Auth;
use MdTech\MdLog\Facades\MdLog;

trait HasLog{

    public function log(string $message, array $addition = []){
        MdLog::create($this->getTable())->info($message, Auth::user()->id ?? 0, $this->id, $this->generateLogData($addition));
    }

    public function generateLogData(array $addition = []){
        return array_merge($model->toArray(), $addition);
    }
}