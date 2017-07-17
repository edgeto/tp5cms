<?php
/**
 * 广告位控制器
 * Class AdPosition
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\AdPositionService;
use Services\SiteService;

class AdPosition extends Base
{

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index($site_id = 0)
	{
		if(empty($site_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}
		$map = array();
		$map['site_id'] = $site_id;
		$res = AdPositionService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = SiteService::getInstance()->getCache();
		$site_name = isset($cache[$site_id]['name']) ? $cache[$site_id]['name'] : '';
		$this->assign('site_id',$site_id);
		$this->assign('site_name',$site_name);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add($site_id = 0)
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = AdPositionService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdPositionService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		if(empty($site_id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("/error/show/msg/{$error}");
		}else{
			$cache = SiteService::getInstance()->getCache();
			$site_name = isset($cache[$site_id]['name']) ? $cache[$site_id]['name'] : '';
			if(empty($site_name)){
				$error = '广告位不存在！';
        		$this->redirect("/error/show/msg/{$error}");
			}
			$this->assign('site_id',$site_id);
			$this->assign('site_name',$site_name);
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
			$res = AdPositionService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = AdPositionService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/adposition/index";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = AdPositionService::getInstance()->getOneById($id);
			if(empty($data)){
				$error = AdPositionService::getInstance()->error;
		        $this->redirect("error/show/msg/{$error}");
			}
			$siteList = SiteService::getInstance()->getCache();
			$this->assign('data',$data);
			$this->assign('siteList',$siteList);
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
		$res = AdPositionService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = AdPositionService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}