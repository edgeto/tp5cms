<?php
/**
 * 角色控制器
 * Class AdminRoleController
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\AdminRoleService;
use Services\ResourceService;
use Libs\Func;

class Adminrole extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index()
	{
		$map = array();
		$res = AdminRoleService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add()
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = AdminRoleService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminRoleService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		return $this->fetch();
	}

	/**
	 * 编辑
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function edit($id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = AdminRoleService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminRoleService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = AdminRoleService::getInstance()->getAdminRoleById($id);
			if(empty($data)){
				$error = AdminRoleService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$this->assign('data',$data);
			return $this->fetch();
		}
	}

	/**
	 * 删除
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function delete($id = 0)
	{
		if(empty($id)){
			$this->code['msg'] = '参数不完整或者参数错误！';
		}
		$res = AdminRoleService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = AdminRoleService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

	/**
	 * 分配权限
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function assignAccess($id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$post['rules'] = implode(',',$post['resource_id']);
			$res = AdminRoleService::getInstance()->editAccess($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminRoleService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}
		$adminRole = AdminRoleService::getInstance()->getAdminRoleById($id);
		if(empty($adminRole)){
			$error = AdminRoleService::getInstance()->error;
	        $this->redirect("error/show/msg/{$error}");
		}
		$adminRole['rules'] = json_encode(explode(',',$adminRole['rules']));
		$reourceList = ResourceService::getInstance()->getCache();
		$Func = new Func();
		$reourceList = $Func->getLevel($reourceList,'id','channel_id');
		$this->assign('adminRole',$adminRole);
		$this->assign('reourceList',$reourceList);
		return $this->fetch();
	}

}