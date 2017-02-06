<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/8/12
 * Time: 18:09
 */

namespace app\framework\controllers;


use app\framework\Helper;
use app\framework\Msg;
use app\framework\StatusCode;

class InterfaceController extends BaseController
{

    const JSONP_CALLBACK = "jsonpCallback";
    /**
     * 是否是DEBUG模式
     * @var bool
     */
    public $debug = false;
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
        //设置接口的header内容
        header('Content-Type: application/json');
        header('Pragma: no-cache');
        header('Cache-Control: no-cache, no-store, max-age=0');
        header('Expires: 1L');
        header('content-type:text/html; charset=utf-8;');
        //获取DEBUG模式
        if(isset($this->queryParams["yii_debug"]) && $this->queryParams["yii_debug"] === "1"){
            $this->debug = true;
        }
        else{
            $this->debug = false;
        }
        if(!$this->checkVisitorAccess()
        && !Helper::isLocalIP()){
            $ret = Msg::failed(StatusCode::CLIENT_ERROR_ACCESS_DENY_CODE,StatusCode::CLIENT_ERROR_ACCESS_DENY_STRING);
            print_r($ret->toJson());
            exit;
        }
    }

    /**
     * 判断接口访问权限
     * @return bool
     */
    public function checkVisitorAccess()
    {
        return $this->terminal->isApp() || $this->debug;
    }


}