<?php 
/**
 * 管理员业务处理器
 * Class AadminService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Config;
use Datas\AdminData;
use Datas\AdminRoleData;
use Datas\AdminLogData;
use Libs\Func;

class AdminService extends BaseService
{

	/**
	 * 必须声明此静态属性，单例模式下防止实例对象覆盖
	 * @var null
	 */
    protected static $instance = null;

    /**
     * 表名
     * @var string
     */
    public $dataName = 'AdminData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_admin_.log'; 

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $Data = 'Datas\\'."$this->dataName";
        // $Data = new $Data;
        $this->Data = $Data::getInstance();
    }

    /**
	 * [checkCookieAdmin description]
	 * @return [type] [description]
	 */
	public function checkCookieAdmin()
    {
    	$admin_id = cookie(md5('admin_auth'));
	    $admin_auth_sign = cookie(md5('admin_auth_sign'));
	    //TODO::登录前操作
	    if(empty($admin_id)){
	        return false;
	    }
	    $adminData = AdminData::getInstance();
		$admin = $adminData->getAdminById($admin_id);
		$auth = array();
		if($admin){
			$auth = array(
	            'id'              	=> $admin['id'],
	            'username'        	=> $admin['username'],
	            'last_login_time' 	=> $admin['last_login_time'],
	            'last_login_ip'	   	=> $admin['last_login_ip'],
	        );
		}
		if($admin_auth_sign != $this->adminAuthSign($auth)){
	        return false;
	    }
	    $this->autoLogin($admin);
    }

    /**
	 * [chekcLogin description]
	 * @param  [type] $username [description]
	 * @param  [type] $password [description]
	 * @return [type]           [description]
	 */
	public function chekcLogin($username = null, $password = null)
	{
		$adminData = AdminData::getInstance();
		$admin = $adminData->getAdminByUsername($username);
		if(empty($admin)){
			$this->error = $adminData->error;
			return false;
		}else{
			if($admin['status'] == 1){
				$this->error = '管理员被禁用！';
				return false;
			}else{
				if(empty($admin['role_id'])){
					$this->error = '您还没有权限，请联系管理员添加权限！';
					return false;
				}else{
					$adminRoleData = AdminRoleData::getInstance();
					$role = $adminRoleData->getAdminRoleById($admin['role_id']);
					if(empty($role) || $role['status'] == 0){
						$this->error = '您还没有权限，请联系管理员添加权限！';
						return false;
					}
					if(empty($role['rules'])){
						// 不是超级管理员
						if(empty($role['is_super'])){
							$this->error = '您还没有权限，请联系管理员添加权限！';
							return false;
						}
					}
				}
				$admin_auth_key = Config::get('admin_auth_key');
                $Func = new Func();
				if($Func->adminMd5($password,$admin_auth_key) == $admin['password']){
					$this->error = '';
					$this->autoLogin($admin);
					return true;
				}else{
					$this->error = '管理员密码错误！';
					return false;
				}
			}
		}
	}


	/**
	 * [autoLogin description]
	 * @param  [type] $admin [description]
	 * @return [type]        [description]
	 */
	public function autoLogin($admin)
	{
		$Func = new Func();
		$admin['last_login_time'] = date("Y-m-d H:i:s");
		$admin['last_login_ip'] = $Func->getClientIp();
		// 记录登录SESSION和COOKIES
        $auth = array(
            'id'              	=> $admin['id'],
            'username'        	=> $admin['username'],
            'last_login_time' 	=> $admin['last_login_time'],
            'last_login_ip'	   	=> $admin['last_login_ip'],
        );
        // 一个月
        cookie(md5('admin_auth'),$admin['id'],2678400);
        cookie(md5('admin_auth_sign'),$this->adminAuthSign($auth),2678400);
        session('admin_auth', $auth);
        session('admin_auth_sign', $this->adminAuthSign($auth));
        $this->updateLogin($admin);
	}

	/**
	 * [adminAuthSign description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function adminAuthSign($data)
	{
		//数据类型检测
	    if(!is_array($data)){
	        $data = (array)$data;
	    }
	    ksort($data); //排序
	    $code = http_build_query($data); //url编码并生成query字符串
	    $sign = sha1($code); //生成签名
	    return $sign;
	}

	/**
     * 更新登陆信息
     * Function updateLogin
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     * @param $admin_id
     * @return bool
     */
    protected function updateLogin($admin)
    {
        if($admin){
    		$adminData = AdminData::getInstance();
    		$adminData->updateLogin($admin);
    		$adminLogData = AdminLogData::getInstance();
    		$adminLogData->addLog($admin);
    	}
    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout()
    {
    	cookie(md5('admin_auth'),null);
        cookie(md5('admin_auth_sign'),null);
        session('admin_auth', null);
        session('admin_auth_sign', null);
        $this->redirect('Login/login');
    }

    /**
     * [isSuper 是否是超级管理员]
     * @param  integer $admin [description]
     * @return boolean        [description]
     */
    public function isSuper($admin_id = 0)
    {
    	$data = array('is_super' => 0,'role_id' => 0,'rules' => '');
    	if($admin_id){
    		$adminData = AdminData::getInstance();
			$admin = $adminData->getAdminById($admin_id);
			$AdminRoleData = AdminRoleData::getInstance();
			$admin_role = $AdminRoleData->getAdminRoleById($admin['role_id']);
			if($admin_role){
				$data['is_super'] = $admin_role['is_super'];
				$data['role_id'] = $admin_role['id'];
				if($admin_role['rules']){
					$rules = explode(',',$admin_role['rules']);
					$rules = array_combine($rules,$rules);
					$data['rules'] = $rules;
				}
			}
    	}
    	return $data;
    }

    /**
     * 通过用户id取记录
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
     */
    public function getAdminById($admin_id = null)
    {
        if(empty($admin_id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $admin = AdminData::getInstance()->getAdminById($admin_id);
        if($admin){
            return $admin;
        }else{
            $this->error = "管理员不存在！";
            return false;
        }
    }

    /**
     * [password description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function password($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if($data['password'] == $data['repassword']){
            $res = AdminData::getInstance()->password($data);
            if(empty($res)){
                $this->error = AdminData::getInstance()->error;
                return false;
            }
            return true;
        }else{
            $this->error = '确认密码不对！';
            return false;
        }
    }

}