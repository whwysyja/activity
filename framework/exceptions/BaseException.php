<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/8/16
 * Time: 17:04
 */

namespace app\framework\exceptions;

use app\framework\StatusCode;
use yii\base\UserException;

class BaseException extends UserException
{
    public function __construct($code = StatusCode::SUCCESS_CODE, $message = "",\Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}