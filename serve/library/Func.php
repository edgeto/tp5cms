<?php
/**
 * 公共函数类
 * Class Func
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Libs;
use think\Config;
use Services\ConfigService;

class Func extends Base
{

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
     * @return mixed
     */
    public function getClientIp($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 是否手机号码
     * @param string|int $number
     * @return int
     */
    public function isPhone($number)
    {
        return preg_match('/^1[356789][1-9]{1}\d{8}$/', $number);
    }

    /**
     * 是否身份证
     * @param string $number
     * @return bool|int
     */
    public function isIdCard($number)
    {
        $isEighteen = preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/", $number);
        if ($isEighteen) {
            return true;
        }
        return preg_match("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/", $number);
    }

    /**
     * 是否正常的时间戳(时间年份大于1970)
     * @param $timestamp
     * @return bool
     */
    public function isTimestamp($timestamp)
    {
        if (strtotime(date('Y-m-d H:i:s', $timestamp)) === $timestamp) {
            return true;
        }
        return false;
    }

    /**
     * 创建一个指定长度的随机密码
     * @param int $pw_length
     * @return string
     */
    public static function create_password($pw_length = 10)
    {
        $texts = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        shuffle($texts);
        $count = count($texts)-1;

        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= $texts[mt_rand(0, $count)];
        }
        return $randpwd;
    }

    /**
     * 获取用户昵称
     * @param string $nickName 用户昵称
     * @return string
     */
    public function getNickName($nickName)
    {
        $temp = '';
        if ($nickName) {
            $temp = substr($nickName, 0, 3);
            $temp .= '****';
            $temp .= substr($nickName, -4);
        }
        return $temp;
    }

    /**
     * [delDirFile description]
     * @param  string $DirFile [description]
     * @return [type]          [description]
     */
    public function delDirFile($DirFile = ''){
        $str = '';
        if($DirFile){
            if(is_dir($DirFile) || is_file($DirFile)){
                if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                    $str = "rmdir /s/q " . $DirFile;
                } else {
                    $str = "rm -Rf " . $DirFile;
                }
            }
        }
        $res = system($str,$retval);
        if($retval == 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * [makeDir description]
     * @param  string $Dir [description]
     * @return [type]      [description]
     */
    public function makeDir($Dir = '')
    {
        $str = '';
        if($Dir && !is_dir($Dir)){
            $str = "mkdir -p " . $Dir;
        }else{
            return false;
        }
        $res = system($str,$retval);
        if($retval == 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 分层
     * @param  array  $reource_list   [description]
     * @param  string $id_name        [description]
     * @param  string $parent_id_name [description]
     * @param  string $son            [description]
     * @return [type]                 [description]
     */
    public function getLevel($reource_list = array(),$id_name = 'id', $parent_id_name = 'pid', $son = 'son'){
        $res = array();
        $tmpData = array();
        foreach ($reource_list as $dataValue) {
            $tmpData[$dataValue[$id_name]] = $dataValue;
        }
        foreach ($tmpData as &$tmpValue) {
            if (isset($tmpData[$tmpValue[$parent_id_name]])) {
                $tmpData[$tmpValue[$parent_id_name]][$son][] = &$tmpData[$tmpValue[$id_name]];
            } else {
                $res[] = &$tmpData[$tmpValue[$id_name]];
            }
        } 
        // 树状等级分类
        $res = $this->levelTree($res);
        return $res;
    }

    /**
     * 管理员密码加密
     * Function admin_md5
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $str
     * @param string $key
     * @return string
     */
    public function adminMd5($str, $key = '')
    {
        if(empty($key)){
            $key = Config::get('admin_auth_key');
        }
        return '' === $str ? '' : md5(sha1($str) . $key);
    }

    /**
     * 文件上传
     * Function upload
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $filename
     * @param $config
     * @return boolean
     */
    public function upload($filename = 'img',$config = ''){
        if($filename){
            $file = request()->file($filename);
            $config = $config ? $config : config('PICTURE_UPLOAD');
            $info = $file->validate($config)->move($config['savePath']);
            if(!$info){
                $this->error = $file->getError();
                return false;
            }else{
                return $config['showUrl'].$info->getSaveName();
            }
        }else{
            $this->error = '没有指定文件post名称';
            return false;
        }
        return true;
    }

    /**
     * select 树状无限分类 selectTree 
     * @param  array  $data           [数据源]
     * @param  array  $level          [等级]
     * @param  array  $res            [返回数据]
     * @param  string $id_name        [字段]
     * @param  string $parent_id_name [父字段]
     * @param  string $son            [子分类]
     * @return [type]                 [description]
     */
    public function levelTree($data = array(),$level = array(),$res = array(),$id_name = 'id', $parent_id_name = 'pid', $son = 'son')
    {
        if($data){
            foreach ($data as $key => &$value) {
                $parent_id = $value[$parent_id_name];
                if(isset($level[$parent_id])){
                    $level[$value[$id_name]] = $level[$parent_id] + 1;
                }else{
                    $level[$value[$id_name]] = 0;
                }
                $value['level'] = $level[$value[$id_name]];
                $res[] = $value;
                if(!empty($value['son'])){
                    $this->levelTree($value['son'],$level,$res);
                }
            }
        }
        return $res;
    }

    /**
     * 树状等级select显示
     * @param  [type] $parent_list [description]
     * @param  string $son         [description]
     * @return [type]              [description]
     */
    public function selectTree($parent_list, $level = '', $son = 'son', $id = 'id', $name = 'name',$option = ''){
        static $option;
        if($parent_list){
            foreach ($parent_list as $key => $val) {
                $level = str_repeat('│　 ',$val['level']);
                if($val == end($parent_list)){
                    $option .= '<option value="'. $val[$id] .'">'. $level .'└─ '. $val[$name ] .'</option>';
                }else{
                    $option .= '<option value="'. $val[$id] .'">'. $level .'├─ '. $val[$name ] .'</option>';
                }
                if(!empty($val[$son])){
                    $this->selectTree($val[$son],$level,$son,$id,$name,$option );
                }
            }
            return $option;
        }
    }

    /**
     * 树状等级select修改显示
     * @param  [type]  $parent_list [description]
     * @param  string  $son         [description]
     * @param  integer $pid         [description]
     * @param  integer $self_id     [description]
     * @return [type]               [description]
     */
    public function selectEditTree($parent_list,$level = '',$pid = 0,$self_id = 0, $son = 'son', $id = 'id', $name = 'name',$option = ''){
        static $option;
        if($parent_list){
            foreach ($parent_list as $key => $val) {
                $level = str_repeat('│　 ',$val['level']);
                if($val[$id] != $self_id){
                    if($val == end($parent_list)){
                        if($val[$id] == $pid){
                            $option .= '<option value="'. $val[$id] .'" selected>'. $level .'└─ '. $val[$name] .'</option>';
                        }else{
                            $option .= '<option value="'. $val[$id] .'">'. $level .'└─ '. $val[$name] .'</option>';
                        }
                    }else{
                        if($val[$id] == $pid){
                            $option .= '<option value="'. $val[$id] .'" selected>'. $level .'├─ '. $val[$name] .'</option>';
                        }else{
                            $option .= '<option value="'. $val[$id] .'">'. $level .'├─ '. $val[$name] .'</option>';
                        }
                    }
                    if(!empty($val[$son])){
                        $this->selectEditTree($val[$son],$level,$pid,$self_id,$son,$id,$name,$option);
                    }
                }
            }
        }
        return $option;
    }

    /**
     * 生成随机字符串
     * @param int $length 生成字符串长度
     * @param bool $symbol 是否包含符号生成字符串长度
     * @param bool $casesensitivity 是否区分大小写，默认区分
     * @return string
     */
    public function generateRandText($length = 8, $symbol=false, $casesensitivity=true){
        // 字母和数字
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        //去掉 小写字母 i o l 大写字母 I O L 数字 0 1 9 figochen 2014-10-09
        $chars = 'abcdefghjkmnprstuvwxyz2345678';
        if($casesensitivity) {
            $chars .= 'ABCDEFGHJKMNPRSTUVWXYZ';
        }
        if($symbol)
        {
            // 标点符号
            $chars .= '!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        }

        $text = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $text .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        if($casesensitivity==false){
            $text=strtolower($text);
        }

        return $text;
    }

    /**
     * 生成随机字符串，字母+数字，可指定字母和数字的数量，位置随机
     * @param int $number_count
     * @param int $char_count
     * @param int $upper_count 大写字母数量
     * @return string
     */
    public function generateSimpleRandText($number_count=4, $char_count=2, $upper_count=0){
        $chars = 'abcdefghjkmnprstuvwxyz';
        $upper='ABCDEFGHJKMNPRSTUVWXYZ';
        $nums = '2345678';

        $arr=array();
        for ( $i = 0; $i < $char_count; $i++ )
        {
            $arr[]=$chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        for ( $i = 0; $i < $upper_count; $i++ )
        {
            $arr[]=$upper[ mt_rand(0, strlen($upper) - 1) ];
        }
        for ( $i = 0; $i < $number_count; $i++ )
        {
            $arr[]=$nums[ mt_rand(0, strlen($nums) - 1) ];
        }
        shuffle($arr);
        $text=implode('', $arr);

        return $text;
    }

    /**
     * curl操作
     * @param $url
     * @param null $post_data
     * @param string $get_post
     * @param bool $http_build_query
     * @param bool $check_ssl
     * @param array $headers
     * @param string $log
     * @return string
     */
    public function curl($url, $post_data=NULL, $get_post='post', $http_build_query=false, $check_ssl=false, $headers=array(),$log=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        if(is_array($headers) && $headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($check_ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        if($get_post == 'post'){
            curl_setopt($ch, CURLOPT_POST, 1);
            if($http_build_query)$post_data = http_build_query($post_data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch,CURLOPT_REFERER ,$url);
        $return = curl_exec($ch);
        curl_close($ch);
        return trim($return);
    }

    /**
     * 是否微信
     * Function is_weixin
     * User: edgeto
     * Date: 2016/07/12
     * Time: 14:00
     * @return bool
     */
    public function isWeixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

}