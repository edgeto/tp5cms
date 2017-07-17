<?php
/**
 * 后台登陆控制器
 * Class Login
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use think\Controller;
use Services\AdminService;

class Login extends Controller
{

    /**
     * 初始化
     * @return [type] [description]
     */
    public function initialize()
    {
        header('X-Powered-By:cms.com');
    }

    /**
     * [$code description]
     * @var array
     */
    public $code = array('code'=>201,'msg'=>'失败','data'=>'');

    /**
     * 后台登陆
     * Function login
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     * @param null $username
     * @param null $password
     */
    public function login($username = null, $password = null)
    {
        // 如果已经登录
        if(session('admin_auth.id')){
            $this->redirect('/');
        }
        if(request()->isPost()){
            $verify_code = request()->post('verify_code');
            if(empty($username) || empty($password) || empty($verify_code)){
                $this->code['msg'] = '参数不完整或者参数错误！';
            }
            $check = captcha_check($verify_code);
            if($check){
                $adminService = AdminService::getInstance();
                $res = $adminService->chekcLogin($username,$password);
                if(empty($res)){
                    $this->code['msg'] = $adminService->error;
                }else{
                    $this->code['code'] = 200;
                    $this->code['msg'] = '登录成功';
                    $this->code['data'] = '/';
                }
            }else{
                $this->error('验证码错误！');
            }

            return $this->code;
        }
        return $this->fetch();
    }

    /**
     * 退出登陆
     * Function logout
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
    public function logout()
    {
        $adminService = AdminService::getInstance();
        $adminService->logout();
    }

}
