<?php
/**
 * 配置控制器
 * Class Config
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\ConfigService;
use Libs\Func;

class Config extends Base
{

	/**
	 * index
	 * @return [type] [description]
	 */
	public function index()
	{
		$list = array();
		$res = ConfigService::getInstance()->getByGroupId();
		if($res){
			$list = $res;
		}
		$this->assign('count',count($list));
		$this->assign('list',$list);
		return $this->fetch();
	}

	/**
	 * 添加分组
	 */
	public function addGroup()
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ConfigService::getInstance()->addGroup($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ConfigService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = '/config/index';
			}
			return $this->code;
		}
		return $this->fetch();
	}

	/**
	 * 编辑分组
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function editGroup($id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ConfigService::getInstance()->editGroup($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ConfigService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = '/config/index';
			}
			return $this->code;
		}else{
			$error = '';
			if(empty($id)){
			 	$error = '参数不完整或者参数错误！';
	            $this->redirect("/error/show/msg/{$error}");
			}
		}
		$data = ConfigService::getInstance()->getById($id);
		if(empty($data)){
			$error = ConfigService::getInstance()->error;
	        $this->redirect("/error/show/msg/{$error}");
		}
		$this->assign('data',$data);
		return $this->fetch();
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
		$res = ConfigService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = ConfigService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

	/**
	 * 列表
	 * @param  integer $group_id [description]
	 * @param  integer $p          [description]
	 * @return [type]              [description]
	 */
	public function listing($group_id = 0)
	{
		if(empty($group_id)){
		 	$error = '参数不完整或者参数错误！';
            $this->redirect("/error/show/msg/{$error}");
		}
		$map['group_id'] = $group_id;
		$res = ConfigService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = ConfigService::getInstance()->getCache();
		$group_name = isset($cache[$group_id]['config_name']) ? $cache[$group_id]['config_name'] : '';
		$this->assign('group_id',$group_id);
		$this->assign('group_name',$group_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 * @param integer $group_id [description]
	 */
	public function add($group_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ConfigService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ConfigService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/config/list/{$post['group_id']}";
			}
			return $this->code;
		}
		if(empty($group_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$group = ConfigService::getInstance()->getById($group_id);
			if(empty($group)){
				$error = ConfigService::getInstance()->error;
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('group',$group);
			return $this->fetch();
		}
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
			$res = ConfigService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ConfigService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/config/listing";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$data = ConfigService::getInstance()->getById($id);
			if(empty($data)){
				$error = ConfigService::getInstance()->error;
		        $this->redirect("/error/show/msg/{$error}");
			}
			$group = ConfigService::getInstance()->getGroupCache();
			if(empty($group)){
				$error = ConfigService::getInstance()->error;
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('data',$data);
			$this->assign('group',$group);
			return $this->fetch();
		}
	}


	/**
	 * [cacheWebAction description]
	 * @return [type] [description]
	 */
	public function cacheWeb()
	{
		if(request()->isPost()){
			$post = input('post.');
			if($post){
				$Func = new Func();
				$Func->delDirFile(RUNTIME_PATH);
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		return $this->fetch();
	}

}