<?php
/**
 * 微信API
 * Class WeiXinApi
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Libs;
use Libs\Base;
use Libs\CacheRedis;
use Libs\Func;

class WeiXinApi extends Base
{

	/**
	 * [$_appId description]
	 * @var string
	 */
	protected $_appId = 'wx9c7be663472ae42a';

	/**
	 * [$_appSecret description]
	 * @var string
	 */
    protected $_appSecret = 'f965ff2128ab5c0e95f7b6e2c403ec08';

    /**
     * 微信的缓存时间是7200(秒), 由于会存在时间差, 缓存时间比微信那边要小
     * @var integer
     */
    protected $_expire = 6000;

    /**
     * 填写的URL需要正确响应微信发送的Token验证
     * @var string
     */
    protected $_token = 'yansheng'; 

   	/**
   	 * 回复文本模板
   	 * @var string
   	 */
    protected $_textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";

    /**
   	 * 回复图片模版
   	 * @var string
   	 */
    protected $_imageTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[image]]></MsgType>
                            <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Image>
                            </xml>";

    /**
   	 * 回复语音模板
   	 * @var string
   	 */
    protected $_voiceTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[voice]]></MsgType>
                            <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Voice>
                            </xml>";

    /**
   	 * 回复图文模板
   	 * @var string
   	 */
    protected $_newsTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                            <Articles>%s</Articles>
                            </xml>";
    /**
   	 * @var string
   	 */         
    protected $_itemTpl = "<item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
                            </item>";

   	/**
   	 * [__construct description]
   	 */
    public function __construct()
    {
    	$this->func = new Func();
    	defined('NOW_TIME') || define('NOW_TIME', time());
        $this->fileCache = new CacheRedis();
    }

    /**
     * 得到公众号下接口调用的token
     * Function getAccessToken
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getAccessToken($refresh = false){
        $key = 'wx_access_token.cache';
        $access_token = $this->fileCache->getValue($key);
        if(!$access_token || $refresh){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appId}&secret={$this->_appSecret}";
            if(ENVIRONMENR == 'DEVELOPMENT'){
                //本地
                $res = file_get_contents($url);
            }else{
                $res = $this->func->curl($url);
            }
            if($res){
                $res = json_decode($res, 1);
                if(!empty($res['access_token'])){
                    $this->fileCache->setValue($key,$res['access_token'],$this->_expire);
                    $access_token = $res['access_token'];
                }
            }
        }
        return $access_token;
    }

    /**
     * 微信OAuth2.0网页授权认证 网页授权获取用户基本信息功能
     * 得到CODE值(貌似不能通过CURL拿CODE值)
     * Function getOauthCodeUrl
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getOauthCodeUrl($params=array()){
        $redirect_url = empty($params['redirect_url']) ? 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] : $params['redirect_url'];
        $scope = empty($params['scope']) ? 'snsapi_base' : $params['scope'];
        $state = $this->func->generateRandText();
        $redirect_url = urlencode($redirect_url);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appId}&redirect_uri={$redirect_url}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * 微信公众平台开发OAuth2.0网页授权认证 网页授权获取用户基本信息功能
     * 网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同
     * @param string $code
     * @return mixed
     */
    public function getOauthAccessToken($code = ''){
        $key = 'wx_oauth_access_token.cache';
        $access_token = $this->fileCache->getValue($key);
        if(!$access_token || $code){
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appId}&secret={$this->_appSecret}&code={$code}&grant_type=authorization_code";
            if(ENVIRONMENR == 'DEVELOPMENT'){
                //本地
                $res = file_get_contents($url);
            }else{
                $res = $this->func->curl($url);
            }
            if($res){
                $res = json_decode($res, 1);
                if(!empty($res['access_token'])){
                    $this->fileCache->setValue($key,$res['access_token'],$this->_expire);
                    $access_token = $res['access_token'];
                }
                $access_token = $res;
            }
        }
        return $access_token;
    }

    /**
     * 获取OpenId
     * @param array $params
     * @return bool
     */
    public function getOpenId($params=array()){
        $state = input('state');
        $code = input('code');
        if (empty($state) && empty($code)) {
            header('Location: '.$this->getOauthCodeUrl($params));
            die();
        }
        $oauth_access_token = $this->getOauthAccessToken($code);
        if (!empty($oauth_access_token['openid'])) {
            return $oauth_access_token['openid'];
        }
        return false;
    }

    /**
     * 微信公众平台开发OAuth2.0网页授权认证 网页授权获取用户基本信息
     * Function getOauthUserInfo
     * User: edgeto
     * Date: 2016/07/08
     * Time: 10:00
     */
    public function getOauthUserInfo($openid){
        $key = 'wx_oauth_access_token.cache';
        $access_token = $this->fileCache->getValue($key);
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        if(ENVIRONMENR == 'DEVELOPMENT'){
            //本地
            $res = file_get_contents($url);
        }else{
            $res = $this->func->curl($url);
        }
        return json_decode($res, 1);
    }

    /**
     * 获取用户基本信息(UnionID机制) 与 网页授权获取用户基本信息不同
     * Function getOauthUserInfo
     * User: edgeto
     * Date: 2016/07/08
     * Time: 10:00
     */
    public function getOauthUserInfo_s($openid){
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        if(ENVIRONMENR == 'DEVELOPMENT'){
            //本地
            $res = file_get_contents($url);
        }else{
            $res = $this->func->curl($url);
        }
        return json_decode($res, 1);
    }

    /**
     * 接收消息主程序
     * Function notify
     * User: edgeto
     * Date: 2016/07/08
     * Time: 10:00
     */
    public function notify(){
        //首次接入
        $signature = input('signature');
        $timestamp = input('timestamp');
        $nonce = input('nonce');
        $echoStr = input('echostr');
        if(!empty($signature) && !empty($timestamp) && !empty($nonce) && !empty($echoStr)){
            $this->checkSignature();
        }
        $this->responseMsg();
    }

    /**
     * 验证消息的确来自微信服务器
     * Function checkSignature
     * User: edgeto
     * Date: 2016/07/08
     * Time: 10:00
     */
    protected function checkSignature(){
        $signature = input('signature');
        $timestamp = input('timestamp');
        $nonce = input('nonce');
        $echoStr = input('echostr');
        $token = $this->_token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
       /* error_log(serialize(I('get.'))."\n",3,'/tmp/weixinnotify.log');
        error_log(serialize($tmpStr)."\n",3,'/tmp/weixinnotify.log');
        error_log(serialize($tmpStr == $signature)."\n",3,'/tmp/weixinnotify.log');
        error_log(serialize($echoStr)."\n",3,'/tmp/weixinnotify.log');*/
        if( $tmpStr == $signature ){
            echo $echoStr;exit;
        }else{
            echo 'wrong';exit;
        }
    }

    /**
     * 微信自动回复
     * Function responseMsg
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     */
    public function responseMsg(){
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
        if (empty($postStr)){
            $postStr = file_get_contents("php://input");
        }
        error_log(date("Y-m-d H:i:s")."\n".serialize($postStr)."\n",3,'/tmp/weixinnotify.log');
        if(!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $msgType = trim($postObj->MsgType);
            switch($msgType){
                case 'text';
                    $this->_response_text($postObj);
                    break;
                case 'image';
                    //$this->_response_image($postObj);
                    $this->_response_news($postObj);
                    break;
                case 'voice';
                    $this->_response_voice($postObj);
                    break;
                case 'event';
                    $this->_response_event($postObj);
                    break;

            }
        }
    }

    /**
     * 语音回复
     * Function _response_voice
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _response_voice($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $MediaId = $postObj->MediaId;//语音内容
        $time = NOW_TIME;
        $resultStr = sprintf($this->_voiceTpl, $fromUsername, $toUsername, $time, $MediaId);
        echo $resultStr;exit;
    }

    /**
     * 图文回复
     * Function _response_news
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _response_news($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = NOW_TIME;
        $ArticleCount = 1;//图文消息个数，限制为10条以内
        $MediaId = $postObj->MediaId;//图片
        //单个
        $title = '测试标题';//图文消息标题
        $desc = '测试描述';//图文消息描述
        $PicUrl = $postObj->PicUrl;//图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
        $Url = "http://www.baidu.com";//点击图文消息跳转链接
        $itemTpl = sprintf($this->_itemTpl, $title, $desc, $PicUrl, $Url);
        $resultStr = sprintf($this->_newsTpl, $fromUsername, $toUsername, $time, $ArticleCount,$itemTpl);
        echo $resultStr;exit;
    }

    /**
     * 图片回复
     * Function _response_image
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _response_image($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $MediaId = $postObj->MediaId;//图片
        $time = NOW_TIME;
        $resultStr = sprintf($this->_imageTpl, $fromUsername, $toUsername, $time, $MediaId);
        echo $resultStr;exit;
    }

    /**
     * 文本回复
     * Function _response_text
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _response_text($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $contentStr = '这里是自动回复测试,您发送的是<a href="http://www.baidu.com">百度</a>'.$keyword;
        $time = NOW_TIME;
        $resultStr = sprintf($this->_textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;exit;
    }

    /**
     * 接收事件推送
     * Function _response_event
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _response_event($postObj){
       $Event = $postObj->Event;
        switch($Event){
            case 'subscribe';//关注
                $this->_event_subscribe($postObj);
                break;
            case 'CLICK';//自定义菜单事件
                $this->_event_menu_click($postObj);
                break;
            case 'SCAN';//用户已关注时的事件推送
                $this->_event_scan($postObj);
                break;
        }
    }

    /**
     * 关注事件推送
     * Function _event_subscribe
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _event_subscribe($postObj){
        $fromUsername = $postObj->FromUserName;//关注人
        $toUsername = $postObj->ToUserName;//微信公众号
        $contentStr = '这只是关注的测试';
        $time = NOW_TIME;
        $resultStr = sprintf($this->_textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;exit;
    }

    /**
     * 自定义菜单点击事件推送
     * Function _event_menu_click
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _event_menu_click($postObj){
        $fromUsername = $postObj->FromUserName;//关注人
        $toUsername = $postObj->ToUserName;//微信公众号
        $EventKey = $postObj->EventKey;//事件KEY值，与自定义菜单接口中KEY值对应,通过此值来进行数据库相关操作
        $contentStr = '这只是关注的测试,你点击的菜单键值是'.$EventKey;
        $time = NOW_TIME;
        $resultStr = sprintf($this->_textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;exit;
    }

    /**
     * 用户已关注时的事件推送
     * Function _event_scan
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param $postObj
     */
    public function _event_scan($postObj){
        $fromUsername = $postObj->FromUserName;//关注人
        $toUsername = $postObj->ToUserName;//微信公众号
        $EventKey = $postObj->EventKey;//事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
        $Ticket = $postObj->Ticket;//二维码的ticket，可用来换取二维码图片
        $contentStr = '这只是关注的测试,你点击的菜单键值是'.$EventKey.'---'.$Ticket;
        $time = NOW_TIME;
        $resultStr = sprintf($this->_textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;exit;
    }

    /**
     * JS-JDK下调用时所需的另外一个ticket token
     * Function getJsApiTicket
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @return mixed
     */
    public function getJsApiTicket(){
        $key = 'wx_jsapi_ticket.cache';
        $jsapi_ticket = $this->fileCache->getValue($key);
        if (empty($jsapi_ticket)) {
            $accessToken = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
            if(ENVIRONMENR == 'DEVELOPMENT'){
                //本地
                $res = file_get_contents($url);
            }else{
                $res = $this->func->curl($url);
            }
            if($res){
                $res = json_decode($res, 1);
                if(!empty($res['ticket'])){
                    $this->fileCache->setValue($key,$res['ticket'],$this->_expire);
                    $jsapi_ticket = $res['ticket'];
                }
            }
        }
        return $jsapi_ticket;
    }

    /**
     * JS-JDK下调用前的配置参数
     * Function getSign
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @return array
     */
    public function getSign(){
        $jsapiTicket = $this->getJsApiTicket();
        $http = 'http://';
        $url = $http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        /*if(!empty($_SERVER['QUERY_STRING'])){
            $url .= '?'.$_SERVER['QUERY_STRING'];
        }*/
        $timestamp = NOW_TIME;
        $nonceStr = $this->func->generateRandText();
        //按照 key 值ASCII码升序排序
        $plain = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($plain);
        $signPackage = array(
            "appId"     => $this->_appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $plain
        );
        return $signPackage;
    }

    /**
     * JS-JDK下初始化数据
     * Function genConfig
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @return string
     */
    public function genConfig(){
        $signs = $this->getSign();
        $config = <<<EOT
        wx.config({
            debug: false,
            appId: '{$signs['appId']}',
            timestamp: {$signs['timestamp']},
            nonceStr: '{$signs['nonceStr']}',
            signature: '{$signs['signature']}',
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'chooseWXPay',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ]
        });
EOT;
        return $config;
    }

    /**
     * 长链接转短链接接口
     * Function getShortUrl
     * User: edgeto
     * Date: 2016/07/08
     * Time: 12:00
     * @param string $long_url
     */
    public function getShortUrl($long_url = ''){
        $http = 'http://';
        $long_url = $long_url ? $long_url :  $http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $_url = input('url');
        $long_url = $_url ? $_url :$long_url;
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
        $post_data = array(
            'action' => 'long2short',
            'long_url' => $long_url,
        );
        $post_data = json_encode($post_data);
        $res = $this->func->curl($url,$post_data);
        if($res){
            $res = json_decode($res, 1);
        }
        dump($res);exit;
    }

    public function test(){
        $openId = $this->getOpenId();
        $userInfo_s = $this->getOauthUserInfo_s($openId);
        $userInfo = $this->getOauthUserInfo($openId);
        dump($userInfo_s);
        dump($userInfo);exit;
    }

}