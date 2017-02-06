<?php
/**
 * -------------------------
 * curl common http request
 * -------------------------
 *
 * Desc: curl common get http request��post request
 *
 * User: liangqi
 * Date: 16/9/4
 * Time: ����7:06
 */

namespace WebUtil;


class CURLUtil
{
    /**
     * GET HTTP Request By Curl
     *
     * @param $url
     * @param $header
     *
     * @return mixed
     */
    public static function get($url, $header = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        self::makeHeader($curl, $header);
        $r = curl_exec($curl);
        curl_close($curl);
        return $r;
    }

    /**
     * ���header
     *
     * @param $curl
     * @param $header
     */
    private static function makeHeader($curl, $header){
        //HTTP����ͷ��"Accept-Encoding: "��ֵ��֧�ֵı�����"identity"��"deflate"��"gzip"�����Ϊ���ַ���""������ͷ�ᷢ������֧�ֵı������͡�
        curl_setopt($curl,CURLOPT_ENCODING, '');
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
    }

    /**
     * post request by default format (x-www-form-urlencoded)
     *
     * @param $url
     * @param $data
     * @param $header
     *
     * @return mixed
     */
    public static function post($url, $data, $header = []) {
        $curl = curl_init();
        self::makeHeader($curl, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $r = curl_exec($curl);
        curl_close($curl);
        return $r;
    }

    /**
     * post by raw string
     *
     * @param $url
     * @param $data
     * @param array $header
     * @return mixed
     */
    public static function postByRaw($url, $data, $header = []){
        $curl = curl_init();
        self::makeHeader($curl, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        if (!is_string($data)) {
            $data = json_encode($data);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $r = curl_exec($curl);
        curl_close($curl);
        return $r;
    }

}