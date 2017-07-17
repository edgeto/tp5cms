<?php
/**
 * 导航控制器
 * Class Nav
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\NavService;
use Services\NavPositionService;

class Nav extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index($nav_position_id = 0)
	{
		if(empty($nav_position_id) || !is_numeric($nav_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}
		$map = array();
		$map['nav_position_id'] = $nav_position_id;
		$res = NavService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = NavPositionService::getInstance()->getCache();
		$nav_position_name = isset($cache[$nav_position_id]['name']) ? $cache[$nav_position_id]['name'] : '';
		$this->assign('nav_position_id',$nav_position_id);
		$this->assign('nav_position_name',$nav_position_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add($nav_position_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = NavService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = NavService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($nav_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$cache = NavPositionService::getInstance()->getCache();
			$nav_position = isset($cache[$nav_position_id]) ? $cache[$nav_position_id] : '';
			if(empty($nav_position)){
				$error = '导航位不存在！';
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('nav_position',$nav_position);
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
			$res = NavService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = NavService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/Ad/index";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = NavService::getInstance()->getOneById($id);
			if(empty($data)){
				$error = NavService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$cache = NavPositionService::getInstance()->getCache();
			$nav_position_id = $data['nav_position_id'];
			$nav_position = isset($cache[$nav_position_id]) ? $cache[$nav_position_id] : '';
			$this->assign('data',$data);
			$this->assign('navPositionList',$cache);
			$this->assign('nav_position',$nav_position);
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
		$res = NavService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = NavService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}