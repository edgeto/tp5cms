<?php
/**
 * 微信
 * Class Weixin
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\home\controller;
use Libs\WeiXinApi;
use Libs\CacheRedis;
use Libs\Func;

class Weixin extends Base
{

    public function notify()
    {
        $WeiXinApi = new WeiXinApi();
        $WeiXinApi->notify();
    }
    
    public function test()
    {
        $WeiXinApi = new WeiXinApi();
        // 网页授权方式
        $params['scope'] = 'snsapi_userinfo';
        $openId = $WeiXinApi->getOpenId($params);
        $userInfo_s = $WeiXinApi->getOauthUserInfo_s($openId);
        $userInfo = $WeiXinApi->getOauthUserInfo($openId);
        dump($openId);
        dump($userInfo_s);
        dump($userInfo);exit;
    }

}
