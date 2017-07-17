<?php
/**
 * 管理员数据处理
 * Class AdminData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\Admin;
use Libs\Func;

class AdminData extends BaseData
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
    public $tablName = 'Admin';

    /**
     * 初始化
     */
    public function __construct()
    {
        $Model = 'Models\\'."$this->tablName";
        // $Model = new $Model;
        $this->Model = $Model::getInstance();
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
		$admin = new Admin();
		$where['id'] = $admin_id;
		$admin_user = $admin->where($where)->find();
		if($admin_user){
			return $admin_user;
		}else{
			$this->error = "管理员不存在！";
			return false;
		}
	}

	/**
	 * 通过用户名取记录
	 * @param  [type] $username [description]
	 * @return [type]           [description]
	 */
	public function getAdminByUsername($username = null)
	{
		if(empty($username)){
			$this->error = '参数不完整或者参数错误！';
			return false;
		}
		$admin = new Admin();
		$where['username'] = $username;
        $admin_user = $admin->where($where)->find();
		if($admin_user){
			return $admin_user;
		}else{
			$this->error = "管理员不存在！";
			return false;
		}
	}

	/**
	 * [updateLogin description]
	 * @param  string $data [description]
	 * @return [type]       [description]
	 */
	public function updateLogin($data = '')
	{
		if($data){
			$Func = new Func();
			$Admin = new Admin();
			$map['id'] = $data['id'];
			$mapdata['login'] = array('exp', '`login`+1');
			$mapdata['last_login_time'] = $data['last_login_time'];
			$mapdata['last_login_ip'] = $data['last_login_ip'];
			$Admin->save($mapdata,$map);
		}
	}

    /**
     * [add description]
     * @param array $data [description]
     */
    public function add($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Admin = new Admin();
        $result = $Admin->allowField(true)->validate(true)->save($data);
        if(empty($result)){
            $this->error = $Admin->getError();
            return false;
        }
        return true;
    }

    /**
     * [edit description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function edit($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Admin = new Admin();
        $res = $Admin->allowField(true)->validate('Admin.edit')->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $Admin->getError();
            return false;
        }
        return true;
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
        $Admin = new Admin();
        $res = $Admin->allowField(true)->validate('Admin.password')->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $Admin->getError();
            return false;
        }
        return true;
    }

}