<?php

namespace app\framework\utils;
use app\framework\Helper;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/10/13
 * Time: 11:20
 */
class FileTargetEx extends FileTarget
{
    /**
     * Formats a log message for display as a string.
     * @param array $message the log message to be formatted.
     * The message structure follows that in [[Logger::messages]].
     * @return string the formatted message
     */
    //                    'prefix' => function ($message) {
//                        $input = json_decode($message[0]);
//                        $ip = $input->ip;
//                        $file = $input->file;
//                        $method = $input->method;
//                        $terminal = $input->terminal;
//                        $param = $input->param;
//                        $mess = $input->message;
//                        return "[$ip] [$terminal] [$file] [$method] [$mess] [$param]";
//                    }

    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            $text = VarDumper::export($text);
        }
        $traces = [];
        if (isset($message[4])) {
            foreach($message[4] as $trace) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }
        $prefix = $this->getMessagePrefix($message);
        $time = Helper::udate('Y-m-d H:i:s.u');
        $input = json_decode($text);
        if(!is_null($input)){
            $ip = $input->ip;
            $file = $input->file;
            $method = $input->method;
            $terminal = $input->terminal;
            $mes = $input->message;
            $param = $input->param;
            $rid = "rid:".$input->rid;
            return $time . " [$level] [$category]" . "[$ip] [$rid] [$terminal] [$file] [$method] [$mes] [$param]"  . (empty($traces) ? '' : "\n    " . implode("\n    ", $traces));;
        }
        return $time . " [$level] [$category] {$prefix} $text"
        . (empty($traces) ? '' : "\n    " . implode("\n    ", $traces));
    }

}