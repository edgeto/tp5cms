<?php 
/**
 * 管理员角色业务处理
 * Class AdminRoleService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\AdminRoleData;
use Datas\ResourceData;

class AdminRoleService extends BaseService
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
    public $dataName = 'AdminRoleData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_admin_role.log'; 

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
     * 检查权限
     * @param  string $rules [description]
     * @return [type]        [description]
     */
    public function filterAccess($rules = '')
    {
    	$controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        $resourceData = ResourceData::getInstance();
        $resource = $resourceData->getIdByControllerAction($controller,$action);
    	if(empty($resource)){
    		$this->error = $resourceData->error;
			return false;
    	}
    	if(!isset($rules[$resource])){
    		$this->error = '您还没有权限，请联系管理员添加权限！';
    		return false;
    	}
    	return true;
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
        $admin_role = AdminRoleData::getInstance()->getAdminRoleById($role_id);
        if($admin_role){
            return $admin_role;
        }else{
            $this->error = AdminRoleData::getInstance()->error;
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
        $res = AdminRoleData::getInstance()->editAccess($data);
        if(empty($res)){
            $this->error = AdminRoleData::getInstance()->error;
            return false;
        }
        $this->cache();
        return true;
    }

}