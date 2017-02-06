<?php

namespace app\framework\interceptors;

use app\framework\Helper;
use app\framework\Msg;
use app\framework\StatusCode;
use Yii;
use yii\web\ErrorHandler;
use yii\web\Response;

use app\framework\exceptions\BaseException;

/**
 * Created by PhpStorm.
 * User: Arron
 * 自定义异常处理类
 * 必须在配置文件中配置'errorHandler' => ['class'=>'app\framework\interceptors\exceptionResolver'],
 * Date: 2016/8/16
 * Time: 16:38
 */
class exceptionResolver extends ErrorHandler
{
   public $errorView = '@app/views/error/index.php';

    public function renderException($exception)
    {
        Helper::logEx("异常发生,File:%s,Line:=%s,stack_trace:%s",[$exception->getFile(),$exception->getLine(),$exception->getTraceAsString()]);
        if ($exception instanceof BaseException){
            $msg = Msg::failed($exception->getCode(),$exception->getMessage());
        }else{
            $str = $exception->getMessage();
            $code = StatusCode::SERVER_ERROR_CODE;
            if(strstr($str,"MemcachePool")){
                $msg = Msg::failed(StatusCode::SERVER_MEMCACHE_ERROR_CODE,$exception->getMessage());
            }else{
                return parent::renderException($exception);
            }
        }
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            $response->isSent = false;
        } else {
            $response = new Response();
        }
        $response->setStatusCode(200);
        $response->data = $msg->toJson();
        $response->send();
        return;
    }
}