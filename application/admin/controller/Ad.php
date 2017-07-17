<?php
/**
 * 广告控制器
 * Class Ad
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\AdService;
use Services\AdPositionService;

class Ad extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index($ad_position_id = 0)
	{
		if(empty($ad_position_id) || !is_numeric($ad_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}
		$map = array();
		$map['ad_position_id'] = $ad_position_id;
		$res = AdService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = AdPositionService::getInstance()->getCache();
		$ad_position_name = isset($cache[$ad_position_id]['name']) ? $cache[$ad_position_id]['name'] : '';
		$this->assign('ad_position_id',$ad_position_id);
		$this->assign('ad_position_name',$ad_position_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add($ad_position_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = AdService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($ad_position_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$cache = AdPositionService::getInstance()->getCache();
			$ad_position = isset($cache[$ad_position_id]) ? $cache[$ad_position_id] : '';
			if(empty($ad_position)){
				$error = '广告位不存在！';
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('ad_position',$ad_position);
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
			$res = AdService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdService::getInstance()->error;
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
			$data = AdService::getInstance()->getOneById($id);
			if(empty($data)){
				$error = AdService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$cache = AdPositionService::getInstance()->getCache();
			$ad_position_id = $data['ad_position_id'];
			$ad_position = isset($cache[$ad_position_id]) ? $cache[$ad_position_id] : '';
			$this->assign('data',$data);
			$this->assign('adPositionList',$cache);
			$this->assign('ad_position',$ad_position);
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
		$res = AdService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = AdService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}