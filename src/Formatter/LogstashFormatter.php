<?php

namespace MdTech\MdLog\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class LogstashFormatter extends NormalizerFormatter
{

    /**
     * @var string the name of the system for the Logstash log message, used to fill the @source field
     */
    protected $systemName;

    /**
     * @var string an application name for the Logstash log message, used to fill the @type field
     */
    protected $applicationName;

    /**
     * @var string a prefix for 'extra' fields from the Monolog record (optional)
     */
    protected $extraPrefix;

    /**
     * @var string a prefix for 'context' fields from the Monolog record (optional)
     */
    protected $contextPrefix;


    /**
     * @param string $applicationName the application that sends the data, used as the "type" field of logstash
     * @param string $systemName the system/machine name, used as the "source" field of logstash, defaults to the hostname of the machine
     * @param string $extraPrefix prefix for extra keys inside logstash "fields"
     * @param string $contextPrefix prefix for context keys inside logstash "fields", defaults to ctxt_
     */
    public function __construct($applicationName, $systemName = null, $extraPrefix = null, $contextPrefix = 'ctxt_')
    {
        parent::__construct('Y-m-d H:i:s.u');

        $this->systemName = $systemName ?: gethostname();
        $this->applicationName = $applicationName;
        $this->extraPrefix = $extraPrefix;
        $this->contextPrefix = $contextPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record = parent::format($record);

        $message = $this->formatV0($record);

        return $this->toJson($message) . "\n";
    }

    protected function formatV0(array $record)
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = date('Y-m-d H:i:s.u');
        }
        $message = array(
            '@timestamp' => $record['datetime'],
            '@source' => $this->systemName,
//            '@fields' => array(),
        );
        if (isset($record['message'])) {
            $message['@message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['@type'] = $record['channel'];
            $message['@channel'] = $record['channel'];
        }
//        if (isset($record['level'])) {
//            $message['@fields']['level'] = $record['level'];
//        }
        if ($this->applicationName) {
            $message['@type'] = $this->applicationName;
        }
//        if (isset($record['extra']['server'])) {
//            $message['@source_host'] = $record['extra']['server'];
//        }
//        if (isset($record['extra']['url'])) {
//            $message['@source_path'] = $record['extra']['url'];
//        }
//        if (!empty($record['extra'])) {
//            foreach ($record['extra'] as $key => $val) {
//                $message['@fields'][$this->extraPrefix . $key] = $val;
//            }
//        }
        if (!empty($record['context'])) {
            $message['@member_id'] = $record['context']['member_id'] ?? 0;
            $message['@record_id'] = $record['context']['record_id'] ?? 0;
            unset($record['context']['operator']);
            unset($record['context']['record']);
            $message['@data'] =  $record['context']['data'] ?? [];
        }

        return $message;
    }

}
