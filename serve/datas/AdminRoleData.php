<?php
/**
 * 管理员登录日志数据处理
 * Class AdminLogData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\AdminRole;
use Libs\Func;

class AdminRoleData extends BaseData
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
    public $tablName = 'AdminRole';

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
	public function getAdminRoleById($role_id = 0)
	{
		if(empty($role_id)){
			$this->error = '参数不完整或者参数错误！';
			return false;
		}
		$AdminRole = new AdminRole();
		$where['id'] = $role_id;
        $admin_role = $AdminRole->where($where)->find();
		if($admin_role){
			return $admin_role;
		}else{
			$this->error = "管理员角色不存在！";
			return false;
		}
	}

    /**
     * [edit description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function editAccess($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $AdminRole = new AdminRole();
        $res = $AdminRole->allowField(true)->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $AdminRole->getError();
            return false;
        }
        return true;
    }


}