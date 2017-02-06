<?php
/**
 * Created by PhpStorm.
 * User: pcdalao
 * Date: 2016/8/8
 * Time: 15:55
 */

namespace app\framework;


class Msg
{
    const SUCCESS_MSG = 'success';

    /**
     * ״̬��
     * @var string
     */
    public $code = StatusCode::SUCCESS_CODE;

    /**
     * ������ʾ
     * @var string
     */
    public $msg = 'success';

    public $rid = null;

    /**
     * ����
     * @var null
     */
    public $data = null;



    /**
     * @param $code
     * @param $message
     * @return Msg
     */
    public static function failed($code = StatusCode::SERVER_ERROR_CODE,$message = StatusCode::SERVER_ERROR_STRING){
        $msg =  new Msg();
        $msg->code = $code;
        $msg->msg = $message;
        $msg->rid = isset(\Yii::$app->controller->rid)?\Yii::$app->controller->rid:'';
        $msg->data = null;
        return $msg;
    }

    /**
     * @param $data
     * @return Msg
     */
    public static function success($data = null){
        $msg =  new Msg();
        $msg->code = StatusCode::SUCCESS_CODE;
        $msg->msg = Msg::SUCCESS_MSG;
        $msg->rid = isset(\Yii::$app->controller->rid)?\Yii::$app->controller->rid:'';
        $msg->data = $data;
        return $msg;
    }

    /**
     * @return string 转换为JSON格式
     */
    public function toJson(){
        return json_encode($this,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return bool
     * 是否成功
     */
    public function isOk(){
        return $this->code == StatusCode::SUCCESS_CODE;
    }
}