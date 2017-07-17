<?php
/**
 * 友情链接控制器
 * Class Friendly
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\FriendlyService;
use Services\FriendlyPositionService;

class Friendly extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index($friendly_position_id = 0)
	{
		if(empty($friendly_position_id) || !is_numeric($friendly_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}
		$map = array();
		$map['friendly_position_id'] = $friendly_position_id;
		$res = FriendlyService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = FriendlyPositionService::getInstance()->getCache();
		$friendly_position_name = isset($cache[$friendly_position_id]['name']) ? $cache[$friendly_position_id]['name'] : '';
		$this->assign('friendly_position_id',$friendly_position_id);
		$this->assign('friendly_position_name',$friendly_position_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add($friendly_position_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = FriendlyService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = FriendlyService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($friendly_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$cache = FriendlyPositionService::getInstance()->getCache();
			$friendly_position = isset($cache[$friendly_position_id]) ? $cache[$friendly_position_id] : '';
			if(empty($friendly_position)){
				$error = '导航位不存在！';
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('friendly_position',$friendly_position);
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
			$res = FriendlyService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = FriendlyService::getInstance()->error;
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
			$data = FriendlyService::getInstance()->getOneById($id);
			if(empty($data)){
				$error = FriendlyService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$cache = FriendlyPositionService::getInstance()->getCache();
			$friendly_position_id = $data['friendly_position_id'];
			$friendly_position = isset($cache[$friendly_position_id]) ? $cache[$friendly_position_id] : '';
			$this->assign('data',$data);
			$this->assign('friendlyPositionList',$cache);
			$this->assign('friendly_position',$friendly_position);
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
		$res = FriendlyService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = FriendlyService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}