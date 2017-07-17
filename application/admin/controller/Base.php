<?php
/**
 * 后台底层控制器
 * Class Base
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use think\Controller;
use think\Config;
use Services;
use Services\AdminService;
use Services\ConfigService;
use Services\ResourceService;
use Services\AdminRoleService;

class Base extends Controller
{

    /**
     * 权取集
     * @var string
     */
    public $role_rules = '';

    /**
     * [$code description]
     * @var array
     */
    public $code = array('code'=>201,'msg'=>'失败','data'=>'');

    /**
     * 后台分页
     * @var integer
     */
    public $pageSize = 10;

    /**
     * Service 业务名称
     * @var string
     */
    public $serviceName = '';

	/**
     * 后台控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
	public function _initialize()
    {
        define("ADMIN_ID",$this->isAdmin());
        if(!ADMIN_ID){// 还没登录 跳转到登录页面
            $this->redirect('/login/login');
        }
        $role_info = $this->isSuper();
        define("IS_SUPER",$role_info['is_super']);
        define("ROLE_ID",$role_info['role_id']);
        $rules['rules'] = $role_info['rules'];
        $this->assign('is_super',IS_SUPER);
        // 配置
        $this->config($rules);
        // 检查权限
        $this->filterAccess();
        // 菜单
        if(!request()->isAjax()){
            $this->assignMenu();
        }
        // 环境配置
        $this->assign('ENVIRONMENR',ENVIRONMENR);
    }

    /**
     * 列表
     * Function index
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     * @return [type] [description]
     */
    public function index()
    {

    }

    /**
     * 管理员是否登录
     * @return boolean [description]
     */
    public function isAdmin()
    {
    	if(session('admin_auth.id')){
	        return session('admin_auth.id');
	    }else{
    		$adminService = AdminService::getInstance();
    		$adminService->checkCookieAdmin();
    		if(session('admin_auth.id')){
    			return session('admin_auth.id');
    		}else{
    			return 0;
    		}
    	}
    }

    /**
     * [isSuper 是否是超级管理员]
     * @return boolean [description]
     */
    public function isSuper()
    {
        $adminService = AdminService::getInstance();
        return $adminService->isSuper(ADMIN_ID);
    }

    /**
     * [config description]
     * @return [type] [description]
     */
    public function config($data = array())
    {
        // 添加配置
        config($data);
        $webConfig['webConfig'] = ConfigService::getInstance()->getSignCache();
        config($webConfig);
        $this->assign('config',config::get());
    }

    /**
     * 检查权限
     * @return [type] [description]
     */
    public function filterAccess()
    {
        if(!IS_SUPER){
            $adminRoleService = AdminRoleService::getInstance();
            $res = $adminRoleService->filterAccess(config('rules'));
            if(empty($res)){
                $controller = strtolower(request()->controller());
                $action = strtolower(request()->action());
                // 默认跳转页
                if($controller == 'index' && $action == 'index'){
                    $route = ResourceService::getInstance()->getNavByIdArr(config('rules'));
                    if($route){
                        $this->redirect($route);
                    }
                }
                $error = $adminRoleService->error;
                if(request()->isAjax()){
                    $this->code['msg'] = $error;
                    echo json_encode($this->code);exit;
                }else{
                    $this->redirect("/Error/show/msg/{$error}");
                }
            }
        }
    }

    /**
     * 菜单
     * @return [type] [description]
     */
    public function assignMenu()
    {
        $leftMenu = $breadCrumbs = array();
        $resourceService = ResourceService::getInstance();
        $menu = $resourceService->getMenu(IS_SUPER,config('rules'));
        if($menu){
            $leftMenu = $menu['leftMenu'];
            $breadCrumbs = $menu['breadCrumbs'];
        }else{
            if(!IS_SUPER){
                // 没有权限
                $error = '您还没有权限，请联系管理员添加权限！';
                $this->redirect("/Error/show/msg/{$error}");
            }
        }
        $this->assign('leftMenu',$leftMenu);
        $this->assign('breadCrumbs',$breadCrumbs);
    }

    /**
     * [_empty description]
     * @return [type] [description]
     */
    public function _empty()
    {
        $this->redirect('/Error/show/msg/找不到这个页面');
    }

}