<?php
/**
 * 区域控制器
 * Class Region
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace app\admin\controller;
use Services\RegionService;
use Libs\Func;

class Region extends Base
{

	/**
     * Service 业务名称
     * @var string
     */
    public $serviceName = 'RegionService';

    /**
     * 后台控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     */
	public function _initialize()
    {
    	parent::_initialize();
    	$Service = 'Services\\'."$this->serviceName";
        // $Data = new $Data;
        $this->Service = $Service::getInstance();
    }

	/**
	 * [indexAction description]
	 * @param  integer $p [description]
	 * @return [type]     [description]
	 */
	public function index()
	{
		$map = array();
		$res = RegionService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$RegionList = RegionService::getInstance()->getCache();
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('RegionList',$RegionList);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add()
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = RegionService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = RegionService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		$RegionList = RegionService::getInstance()->getCache();
		$Func = new Func();
		$RegionList = $Func->getLevel($RegionList);
		$RegionOption = $Func->selectTree($RegionList);
		$this->assign('RegionOption',$RegionOption);
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
			$res = RegionService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = RegionService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/Ad/index";
			}
			return $this->code;
		}
		$data = RegionService::getInstance()->getOneById($id);
		if(empty($data)){
			$error = RegionService::getInstance()->error;
	        $this->redirect("error/show/msg/{$error}");
		}
		$this->assign('data',$data);
		$RegionList = RegionService::getInstance()->getCache();
		$Func = new Func();
		$RegionList = $Func->getLevel($RegionList);
		$RegionOption = $Func->selectEditTree($RegionList,'',$data['pid'],$data['id']);
		$this->assign('RegionOption',$RegionOption);
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
		$res = RegionService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = RegionService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}