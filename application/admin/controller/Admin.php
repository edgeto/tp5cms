<?php
/**
 * 管理员控制器
 * Class AdminController
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\AdminRoleService;
use Services\AdminService;
use Services\AdminLogService;

class Admin extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index()
	{
		$map = array();
		$res = AdminService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$roleList = AdminRoleService::getInstance()->getCache();
		$this->assign('roleList',$roleList);
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
			$res = AdminService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		$roleList = AdminRoleService::getInstance()->getCache();
		$this->assign('roleList',$roleList);
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
			$res = AdminService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/admin/index";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = AdminService::getInstance()->getAdminById($id);
			if(empty($data)){
				$error = AdminService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$roleList = AdminRoleService::getInstance()->getCache();
			$this->assign('data',$data);
			$this->assign('roleList',$roleList);
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
		$res = AdminService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = AdminService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

	/**
	 * 编辑管理员密码
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function AdminPassword($id = 0)
	{
		if(request()->isPost()){
			$post = $post = input('post.');
			$res = AdminService::getInstance()->password($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/admin/index";
			}
			return $this->code;
		}
		$id = $id ? $id : ADMIN_ID;
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = AdminService::getInstance()->getAdminById($id);
			if(empty($data)){
				$error = AdminService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$this->assign('data',$data);
			return $this->fetch('password');
		}
	}
	
	/**
	 * 编辑密码
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function password($id = 0)
	{
		if(request()->isPost()){
			$post = $post = input('post.');
			$res = AdminService::getInstance()->password($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdminService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/admin/index";
			}
			return $this->code;
		}
		$id = $id ? $id : ADMIN_ID;
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = AdminService::getInstance()->getAdminById($id);
			if(empty($data)){
				$error = AdminService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$this->assign('data',$data);
			return $this->fetch();
		}
	}

	/**
	 * [logAction description]
	 * @param  integer $admin_id [description]
	 * @param  integer $p        [description]
	 * @return [type]            [description]
	 */
	public function AdminLog($id = 0)
	{
		if(empty($id)){
		 	$id = ADMIN_ID;
		}
		$map['user_id'] = $id;
		$res = AdminLogService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		// 管理员信息
		$adminList = AdminService::getInstance()->getCache();
		$this->assign('adminList',$adminList);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch('log');
	}

	/**
	 * [logAction description]
	 * @param  integer $admin_id [description]
	 * @param  integer $p        [description]
	 * @return [type]            [description]
	 */
	public function log($id = 0)
	{
		if(empty($id)){
		 	$id = ADMIN_ID;
		}
		$map['user_id'] = $id;
		$res = AdminLogService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		// 管理员信息
		$adminList = AdminService::getInstance()->getCache();
		$this->assign('adminList',$adminList);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

}