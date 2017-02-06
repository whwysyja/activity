<?php
/**
 * Created by PhpStorm.
 * User: pcdalao
 * Date: 2016/8/8
 * Time: 14:29
 */

namespace app\framework;


class Terminal
{
    /**
     * 终端类型 Plugin、Web、APP、WEB
     */
    const WEB = 0;
    const IOS = 1;
    const ANDROID = 2;
    const PLUGIN = 3;
    const WAP = 4;
    const WX = 5;

    /**
     * 终端类型 Plugin、Web、APP、WAP
     */
    public $terminalType = Terminal::WEB;

    /**
     * 终端版本
     */
    public $version = null;

    /**
     * 系统版本 仅Android IOS 有效
     */
    public $osVersion = null;

    /**
     * 应用市场或者捆绑渠道号
     */
    public $channel = null;

    /**
     * 插件注入类型
     */
    public $ats = null;

    /**
     * 手机型号
     */
    public $phoneModel = null;

    /**
     * 当前用户是否为移动终端
     * @return bool
     */
    public function isApp()
    {
        return $this->terminalType == Terminal::IOS ||
        $this->terminalType == Terminal::ANDROID;
    }

    /**
     * 当前用户是否为WAP终端
     * @return bool
     */
    public function isWap()
    {
        return $this->terminalType == Terminal::Wap;
    }

    /**
     * 当前用户是否为微信浏览器
     * @return bool
     */
    public function isWx(){
        return $this->terminalType == Terminal::WX;
    }
    /**
     * @param $userAgent
     * @return Terminal
     */
    static public function parse()
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
//      $userAgent = "Mozilla/5.0 (Linux; U; Android 5.1; zh-CN; m1 metal Build/LMY47I) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.10.8.822 U3/0.8.0 Mobile Safari/534.30";
        $terminal = new Terminal();
        if (empty($userAgent)) {
            return $terminal;
        }
        //获取用户
        $userAgent = strtolower($userAgent);
        //获取推广渠道号
        if (preg_match('@appsource:([^\\s;]+).*@i', $userAgent, $match)) {
            $terminal->channel = $match[1];
        }

        //获取手机型号
        if (preg_match('|(?<=phonemodel:).*(?=[;])|i', $userAgent, $match)) {
            $terminal->phoneModel = isset($match[0])?$match[0]:null;
        }

        //获取插件ATS配置
        if (preg_match('@ats:([^;]+).*@i', $userAgent, $match)) {
            $terminal->ats = $match[1];
        }
        //获取终端、终端版本、OS版本
        if(strstr($userAgent,"micromessenger")){
            $terminal->terminalType = Terminal::WX;
        }else{
            if (preg_match('@yht-([^/]+)/([^\s;\)]+).*@i', $userAgent, $match)) {
                //获取终端、终端版本
                $terminal->version = isset($match[2]) ? $match[2] : null;
                if (strpos($match[0], 'plugin')) {
                    $terminal->terminalType = Terminal::PLUGIN;
                } else if (strpos($match[0], 'app')) {
                    if(strpos($match[0], 'ios')){
                        $terminal->terminalType = Terminal::IOS;}
                    else{
                        $terminal->terminalType = Terminal::ANDROID;
                    }
                }
                //获取OS版本
                if (preg_match('@(?:android|ios):([^\\\\s;\\\\)]+).*@i', $userAgent, $match))
                    $terminal->osVersion = isset($match[1]) ? $match[1] : null;

            } else if (strpos($userAgent, 'mobile')) {
                $terminal->terminalType = Terminal::WAP;
            }
        }
        if ($terminal->osVersion == null
            && preg_match('@(?:android|ios)[:\\s]*([^\\s;]+)@i', $userAgent, $match)
        ) {
            $terminal->osVersion = $match[1];
        }
        return $terminal;
    }
}
