<?php
/**
 * 错误控制器,不能继承Base,因为Base里面有权限判断
 * Class Error
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use think\Request;
use think\Controller;
use think\Config;

class Error extends Controller
{

    /**
     * 错误控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
    public function _initialize()
    {
        // 环境配置
        $this->assign('ENVIRONMENR',ENVIRONMENR);
        // 配置
        $this->assign('config',Config::get());
    }

	/**
     * 控制器不存在
     * Function index
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
	public function index(Request $request)
    {
        // abort(404, '页面不存在');
        $this->redirect('/Error/show/msg/页面不存在!');
    }	

    /**
     * 方法不存在
     * Function _empty
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
    public function _empty()
    {
       	// abort(404, '页面不存在');
        $this->redirect('/Error/show/msg/方法不存在!');
    }

    /**
     * [show description]
     * @param  string $msg [description]
     * @return [type]      [description]
     */
    public function show($msg = 'Page Not Found')
    {
        $this->assign('msg',$msg);
        return $this->fetch();
    }
    
}