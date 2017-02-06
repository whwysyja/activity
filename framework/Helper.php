<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/8/9
 * Time: 19:19
 */

namespace app\framework;

use app\components\ZRequest;
use Yii;
use yii\data\Pagination;

class Helper
{
    const LOG_LEVEL_DEBUG = 1;
    const LOG_LEVEL_INFO = 2;
    const LOG_LEVEL_WARNING = 3;
    const LOG_LEVEL_ERROR = 4;
    const LOG_LEVEL_SEARCH = 5;
    /**
     * 输出log
     * @param $line
     * @param string $message
     */
    public static function log($message = 'success', $level = Helper::LOG_LEVEL_INFO)
    {
        $res['ip'] = Helper::getRemoteIp();
        $terminal = Terminal::parse();
        $res["terminal"] = json_encode($terminal,JSON_UNESCAPED_SLASHES);
        $res['file'] = isset(Yii::$app->controller) ? Yii::$app->controller->className() : "unknownClass";
        $res['method'] = (isset(Yii::$app->controller) && (Yii::$app->controller->action)) ? Yii::$app->controller->action->id : "unknownAction";;
        $res['param'] = isset(Yii::$app->controller) ? json_encode(Yii::$app->controller->queryParams,JSON_UNESCAPED_SLASHES) : "unknownParams";
        $res['message'] = $message;
        $res['rid'] = isset(Yii::$app->controller->rid)?Yii::$app->controller->rid:'';
        $text = json_encode($res,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,2);
        if ($level == Helper::LOG_LEVEL_ERROR) {
            Yii::info($text, 'm-error');
        } else if ($level == Helper::LOG_LEVEL_WARNING) {
            Yii::info($text, 'm-warning');
        }  else if ($level == Helper::LOG_LEVEL_SEARCH){
            Yii::info($text, 'm-search');
        }else {
            Yii::info($text, 'm');
        }
    }

    /**
     * @param $format
     * @param null $params array或者object
     * @param int $level
     */
    public static function logEx($format, $params = null,$level = Helper::LOG_LEVEL_INFO)
    {
        if ($params == null) {
            $params = "";
        }
        if(!is_array($params)){
            $temp =  $params;
            $params = array();
            $params[] = $temp;
        }
        $new = array();
        foreach ($params as $param) {
            if (!is_string($param)) {
                $param = json_encode($param, JSON_UNESCAPED_UNICODE);
            }
            $new[] = $param;
        }
        $txt = vsprintf($format, $new);
        self::log($txt, $level);
    }

    /**
     * 获取用户真实的IP地址
     * @return string
     */
    public static function getRemoteIp()
    {
        $ip = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : null;
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        }
        return $ip;
    }
    
    /**
     * Judge the server ip is local or not.
     *
     * @access public
     * @return void
     */
    public static function isLocalIP()
    {
        $serverIP = $_SERVER['SERVER_ADDR'];
        if ($serverIP == '127.0.0.1') return true;
        return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    /**
     * Judge a string is utf-8 or not.
     *
     * @param  string $string
     * @author hmdker@gmail.com
     * @see    http://php.net/manual/en/function.mb-detect-encoding.php
     * @static
     * @access public
     * @return bool
     */
    static public function isUTF8($string)
    {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($string);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($string[$i]);
            if ($c > 128) {
                if (($c >= 254)) return false;
                elseif ($c >= 252) $bits = 6;
                elseif ($c >= 248) $bits = 5;
                elseif ($c >= 240) $bits = 4;
                elseif ($c >= 224) $bits = 3;
                elseif ($c >= 192) $bits = 2;
                else return false;
                if (($i + $bits) > $len) return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($string[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    /**
     * Create safe base64 encoded string for the framework.
     *
     * @param   string $string the string to encode.
     * @static
     * @access  public
     * @return  string  encoded string.
     */
    static public function safe64Encode($string)
    {
        return strtr(base64_encode($string), '/', '.');
    }

    /**
     * Decode the string encoded by safe64Encode.
     *
     * @param   string $string the string to decode
     * @static
     * @access  public
     * @return  string  decoded string.
     */
    static public function safe64Decode($string)
    {
        return base64_decode(strtr($string, '.', '/'));
    }

    /**
     * 切割字符串
     * @param $string
     * @param $delimiter
     * @param bool|true $unique 去除重复值
     * @param bool|true $trim 去除控制
     * @return array
     */
    public static function splitString($string, $delimiter = ";", $unique = true, $trim = true)
    {
        if (empty($string))
            return null;
        $arr = explode($delimiter, $string);
        if ($unique)
            $arr = array_unique($arr); //去掉重复的元数
        if ($trim)
            $arr = array_filter($arr); //去掉空值
        return array_values($arr);
    }

    /**
     * @param $s0
     * @return null|string
     * php获取汉字拼音的第一个字母
     */
    public static function getFirstChar($s0)
    {
        $s0 = ucfirst(trim($s0));
        if (isset($s0{0}) and $s0{0} != null) {
            $firstchar_ord = ord(strtoupper($s0{0}));
        } else {
            return null;
        }
        if (($firstchar_ord >= 65 and $firstchar_ord <= 91) or ($firstchar_ord >= 48 and $firstchar_ord <= 57)) return $s0{0};
        $s = iconv("UTF-8", "gb2312//IGNORE", $s0);
        if (isset($s{0}) and $s{0} != null and isset($s{1}) and $s{1} != null) {
            $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        } else {
            return null;
        }
        if ($asc <= -20320) return "#";
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17923) return "G";
        if ($asc >= -17922 and $asc <= -17418) return "H";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
        if ($asc >= -10246) return "#";
        return null;
    }

    public static function udate($format = 'u', $utimestamp = null) {
        if (is_null($utimestamp))
            $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
}