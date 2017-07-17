<?php
/**
 * 资源
 * Class Resource
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use Services\ResourceService;

class Resource extends Base
{

	/**
	 * 频道列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$list = array();
		$res = ResourceService::getInstance()->getByChannelId();
		if($res){
			$list = $res;
		}
		$this->assign('count',count($list));
		$this->assign('list',$list);
		return $this->fetch();
	}

	/**
	 * 添加频道
	 */
	public function addChannel()
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ResourceService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ResourceService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = '/resource/index';
			}
			return $this->code;
		}
		return $this->fetch();
	}

	/**
	 * 编辑频道
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function editChannel($id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ResourceService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ResourceService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = '/resource/index';
			}
			return $this->code;
		}else{
			$error = '';
			if(empty($id)){
			 	$error = '参数不完整或者参数错误！';
	            $this->redirect("/error/show/msg/{$error}");
			}
		}
		$data = ResourceService::getInstance()->getById($id);
		if(empty($data)){
			$error = ResourceService::getInstance()->error;
	        $this->redirect("error/show/msg/{$error}");
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
		$res = ResourceService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = ResourceService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

	/**
	 * 列表
	 * @param  integer $channel_id [description]
	 * @param  integer $p          [description]
	 * @return [type]              [description]
	 */
	public function listing($channel_id = 0)
	{
		if(empty($channel_id)){
		 	$error = '参数不完整或者参数错误！';
            $this->redirect("/error/show/msg/{$error}");
		}
		$map['channel_id'] = $channel_id;
		$res = ResourceService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = ResourceService::getInstance()->getCache();
		$channel_name = isset($cache[$channel_id]['name']) ? $cache[$channel_id]['name'] : '';
		$this->assign('channel_id',$channel_id);
		$this->assign('channel_name',$channel_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 * @param integer $channel_id [description]
	 */
	public function add($channel_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = ResourceService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ResourceService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/resource/listing/channel_id/{$post['channel_id']}";
			}
			return $this->code;
		}
		if(empty($channel_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$channel = ResourceService::getInstance()->getById($channel_id);
			if(empty($channel)){
				$error = ResourceService::getInstance()->error;
        		$this->redirect("error/show/msg/{$error}");
			}
			$this->assign('channel',$channel);
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
			$res = ResourceService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ResourceService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/resource/list/{$post['channel_id']}";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/404/{$error}");
		}else{
			$data = ResourceService::getInstance()->getById($id);
			if(empty($data)){
				$error = ResourceService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$channel = ResourceService::getInstance()->getChannelCache();
			if(empty($channel)){
				$error = ResourceService::getInstance()->error;
        		$this->redirect("error/show/msg/{$error}");
			}
			$this->assign('data',$data);
			$this->assign('channel',$channel);
			return $this->fetch();
		}
	}

}