<?php

namespace MdTech\MdLog;

use MdTech\MdLog\Exceptions\LoggerInvalidException;
use MdTech\MdLog\Formatter\LogstashFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MdLog
{
    protected $config;
    protected $channel_config;

    protected $logger;

    public function create(string $channel){
        $this->config = config('md_log');
        $this->channel_config = $this->config['channel'][$channel] ?? false;

        if(!$this->channel_config)
            throw new LoggerInvalidException();

        $this->logger = new Logger($channel, [self::getHandler($this->filePath(), $this->getAppName())]);

        return $this;
    }

    public function info(string $message, int $member_id, int $record_id, array $data){
        $this->logger->info($message, [
            'member_id' => $member_id,
            'record_id' => $record_id,
            'data' => $data
        ]);
    }

    public function getLogger(){
        return $this->logger;
    }

    protected function filePath(){
        return storage_path($this->config['path'] . '/' . ($this->channel_config['filename'] ?? $this->config['default_filename']));
    }

    protected function getHandler(string $file, string $name){
        return (new StreamHandler($file, Logger::INFO))
            ->setFormatter(new LogstashFormatter($name, 'mdr'));
    }

    protected function getAppName(){
        return explode('/', app('request')->path())[0] ?? null;
    }
}