<?php

namespace app\framework\controllers;

use app\components\ZRequest;
use app\framework\Terminal;
use Yii;
use yii\web\Controller;

/**
 * Base controller
 */
class BaseController extends Controller
{
    /**
     * The $request object, used to access the yii::$app var.
     *
     * @var ojbect
     * @access public
     */
    public $app;

    /**
     * The $request object, used to access the yii::$app->request var.
     *
     * @var ojbect
     * @access public
     */
    public $request;

    /**
     * The $response object, used to access the yii::$app->response var.
     *
     * @var ojbect
     * @access public
     */
    public $response;

    /**
     * The $cookie object, used to access the request $_COOKIE var.
     *
     * @var ojbect
     * @access public
     */
    public $cookie;

    /**
     * The $session object, used to access the $_SESSION var.
     *
     * @var ojbect
     * @access public
     */
    public $cache;

    /**
     * The $session object, used to access the $_SESSION var.
     *
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * The $terminal object, used to access the terminal var.
     * 获取用户终端信息
     * @var ojbect
     * @access public
     */
    public $terminal;

    /**
     * 查询参数，支持GET、POST、PUT方法
     * @var array
     */
    public $queryParams;

    /**
     * @var
     * rid
     */
    public $rid;

    public function init()
    {
        $this->enableCsrfValidation = false;

        $this->app  = Yii::$app;
        $this->request    = Yii::$app->getRequest();
        $this->response    = Yii::$app->getResponse();
        $this->cookie  = Yii::$app->request->getCookies();
        $this->cache = Yii::$app->getCache();
        $this->session = Yii::$app->getSession();
        $this->terminal = Terminal::parse();
        
        $this->queryParams = array_merge($this->request->getQueryParams(),$this->request->getBodyParams());
        $this->rid = str_shuffle(ZRequest::getRandom(32-strlen(time())).time());
    }

    /*
     * action绑定参数支持 GET\POST\PUT
     */
    public function bindActionParams($action, $params)
    {
        return parent::bindActionParams($action,$this->queryParams);
    }

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|string
     * JSONP封装和判断
     */
    public function afterAction($action, $result){
        $result = parent::afterAction($action,$result);
        if($this->queryParams != null && !empty( $this->queryParams[InterfaceController::JSONP_CALLBACK])){
            $func = $this->queryParams[InterfaceController::JSONP_CALLBACK];
            return $func . "(" . $result .")";
        }
        return $result;
    }
}
