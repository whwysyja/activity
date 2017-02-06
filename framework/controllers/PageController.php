<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/8/12
 * Time: 16:03
 */
namespace app\framework\controllers;

use Yii;

class PageController extends BaseController
{
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

        if(isset(Yii::$app->charset)){
            header('content-type:text/html; charset='.Yii::$app->charset.';');
        }
        Yii::$app->view->params['title'] ='海淘1号-100%海外正品，美亚、日亚、德亚、6PM等全球电商免费一键代购，帮你轻松购遍全球。';
        Yii::$app->view->params['keywords']  = '海淘1号,海外代购,全球购,海淘,海淘网,美国亚马逊,美亚,日亚,德亚,日本亚马逊,6PM';
        Yii::$app->view->params['description']  = '海淘1号，专业的海淘服务平台，100%正品代购保证、免费的全网包税代购服务，与美亚、日亚、德亚、6PM等海外电商网站合作，支持一键购买海外商品，为海淘用户首选平台。';
        $this->layout = "main";
    }
}