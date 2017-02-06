<?php
/**
 * Created by PhpStorm.
 * User: Arron
 * Date: 2016/8/8
 * Time: 14:17
 */
namespace app\framework;


class StatusCode{

    const SUCCESS_CODE  = '2000';
    const SUCCESS_STRING  = 'success';

    const CLIENT_ERROR_ACCESS_TOO_FREQUENTLY_CODE = '40000002'; //接口访问太频繁
    const CLIENT_ERROR_ACCESS_TIMEOUT = '40000003';         //接口访问超时

    const CLIENT_ERROR_ACCESS_DENY_CODE  = '40000004';    //访问拒绝
    const CLIENT_ERROR_ACCESS_DENY_STRING  = '访问拒绝';    //访问拒绝

    const CLIENT_EMPTY_PARAMETER_CODE = '40000006'; //参数为空
    const CLIENT_EMPTY_PARAMETER_STRING = '参数为空'; //参数为空

    const CLIENT_ILLEGAL_PARAMETER_CODE  = '40000007';    //参数非法
    const CLIENT_ILLEGAL_PARAMETER_STRING  = '参数非法';    //参数非法

    const CLIENT_DATA_NOT_EXISTS_CODE = '40000008'; //数据不存在
    const CLIENT_DATA_NOT_EXISTS_STRING = '数据不存在'; //数据不存在

    const CLIENT_NOT_LOGIN_CODE = '40000009';//未登录
    const CLIENT_NOT_LOGIN_STRING = '未登录';//未登录

    const CLIENT_DECODE_ERROR_CODE = '40000010';// 解码错误
    const CLIENT_PARAM_FORMAT_ERROR_CODE = '40000011';//格式化错误
    const CLIENT_PARAM_VALIDATE_ERROR_CODE = '40000012';//参数校验失败

    const SERVER_ERROR_CODE  = '50000000';    //服务器常用错误码
    const SERVER_ERROR_STRING  = '服务器内部错误';    //服务器常用错误码


    const SERVER_MEMCACHE_ERROR_CODE  = '50001000';    //服务器常用错误码
    const SERVER_MEMCACHE_ERROR_STRING  = '服务器缓存错误';    //服务器常用错误码

    const SERVER_REDIS_ERROR_CODE  = '50001001';    //服务器常用错误码
    const SERVER_REDIS_ERROR_STRING  = '服务器缓存错误';    //服务器常用错误码

    const SERVER_MYSQL_ERROR_CODE  = '50001002';    //服务器常用错误码
    const SERVER_MYSQL_ERROR_STRING  = '服务器数据库错误';    //服务器常用错误码

    const SERVER_MONGO_ERROR_CODE  = '50001003';    //服务器常用错误码
    const SERVER_MONGO_ERROR_STRING  = '服务器MONGO错误';    //服务器常用错误码

    const SERVER_METAQ_ERROR_CODE  = '50001004';    //服务器常用错误码
    const SERVER_METAQ_ERROR_STRING  = '服务器METAQ错误';    //服务器常用错误码

    const SERVER_RKQ_ERROR_CODE  = '50001005';    //服务器常用错误码
    const SERVER_RKQ_ERROR_STRING  = '服务器RKQ错误';    //服务器常用错误码
}
