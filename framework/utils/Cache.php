<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/11/29
 * Time: 16:47
 */

namespace app\framework\utils;
use app\framework\exceptions\BaseException;
use app\framework\StatusCode;


class CacheTimes
{
    //15秒
    CONST FIFTEEN = 15;
    //30秒
    CONST THIRTY = 30;
    //1分钟
    CONST ONEMINS = 60;
    //2分钟
    CONST TWOMINS = 120;
    //5分钟
    CONST FIFMINS = 300;
    //10分钟
    CONST TENS = 600;
    //15分钟
    CONST FIFTEENMINS = 900;
    //30分钟
    CONST HALFHOUR = 1800;
    //1小时
    CONST ONEHOUR = 3600;
    //6小时
    CONST SIXHOUR = 21600;
    //1天
    CONST ONEDAY = 86400;
    //1周
    CONST WEEKLY = 604800;
    //30天
    CONST THIRTYDAY = 2592000;
    //永久
    //CONST FOREVER = -2;
}

class CacheData
{
    public $expiredTime;
    public $data;

    public function __construct($data, $expiredTime = null){
        $this->data = $data;
        if ($expiredTime == null) {
            $this->expiredTime = null;
        } else {
            $this->expiredTime = time() + $expiredTime;
        }
    }

    /**
     * @return bool
     */
    public function timeout()
    {
        return $this->expiredTime < 1 ? false : (time() > $this->expiredTime);
    }

    /**
     * @return mixed
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }

    /**
     * @param mixed $expiredTime
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}

class Cache
{
    public static function get($key)
    {
        if (empty($key))
            return null;
        $result = \Yii::$app->cache->get($key);
        if ($result && $result instanceof CacheData) {
            if ($result->timeout())
                return null;
            return $result->getData();
        }
        return null;
    }

    public static function set($key, $object, $expiredTime = null)
    {
        if (empty($key))
            throw new BaseException(StatusCode::CLIENT_ILLEGAL_PARAMETER_CODE, StatusCode::CLIENT_ILLEGAL_PARAMETER_STRING);
        $cacheData = new CacheData($object, $expiredTime);
        return \Yii::$app->cache->set($key, $cacheData, $cacheData->expiredTime);
    }
}