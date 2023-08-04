<?php

namespace MdTech\MdLog\Traits;

use Illuminate\Support\Facades\Auth;
use MdTech\MdLog\Facades\MdLog;

trait HasLog{

    public function log(string $message){
        MdLog::create($this->getTable())->info($message, Auth::user()->id, $this->id, $this->generateLogData());
    }

    public function generateLogData(){
        return $model->toArray();
    }
}