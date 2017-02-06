<?php
/**
 * Created by PhpStorm.
 * User: pcdalao
 * Date: 2016/12/6
 * Time: 10:52
 * 告警
 */

namespace app\framework\utils;


use app\components\ZHttp;
use app\framework\Helper;

class Alarm
{
    public $platform;       //告警发生平台
    public $business;       //业务名称
    public $code;           //告警编号
    public $level;          //告警等级
    public $title;          //告警标题
    public $content;        //告警文案
    public $params;

    public function __construct($platform = '1haitao',$business = 'm-server'){
        $this->platform = $platform;
        $this->business = $business;
    }

    public function parse($code,$level,$title,$content,$params){
        $this->code = $code;
        $this->level = $level;
        $this->title = $title;
        $this->content = $content;
        $this->params = $params;
    }
    /**
     * @param $code
     * @param $level
     * @param $title
     * @param $content
     * @param $params
     * 例子：$alarmParams = [
            'platform' => '1haitao',
            'business' => 'm-server',
            'code' => 'm-search-goods',
            'level'=> 1,
            'title' => '搜索商品库接口挂了',
            'content' => '搜索商品库接口挂了，请及时查看',
            'paramMap' => [
                'time' => date('Y-m-d H:i:s',time()),
                'params' => $params,
                ],
            ];
     */
    public static function alarmResult($code,$level=1,$title,$content,$params){
        $alarm = new Alarm();
        $alarm->parse($code,$level,$title,$content,$params);
        $alarmParams = [
            'platform' => $alarm->platform,
            'business' => $alarm->business,
            'code' => $alarm->code,
            'level'=> $alarm->level,
            'title' => DEPLOY.'环境，'.$alarm->title,
            'content' => DEPLOY.'环境，'.$alarm->content,
            'paramMap' => [
                'time' => date('Y-m-d H:i:s',time()),
                'params' => $alarm->params,
            ],
        ];
        $alarmResult = ZHttp::requestApi('alarm', 'alarm', json_encode($alarmParams), 'post');
        if(isset($alarmResult['code']) and $alarmResult['code'] != 2000){
            Helper::log("接入告警系统失败,param:" . json_encode($alarmParams) . "Msg:" . json_encode($alarmResult));
        }
    }
}