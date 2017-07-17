<?php
/**
 * 财务管得
 * Class Money
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use Services\MoneyService;

class Money extends Base
{

	/**
	 * 文章列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$map = array();
		$res = MoneyService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		// 收入
		$PlusMoney = MoneyService::getInstance()->getSumMoneyByPlus();
		// 支出
		$MinusMoney = MoneyService::getInstance()->getSumMoneyByMinus();
		// 余额
		$BalanceMoney = bcadd($PlusMoney,$MinusMoney,2);
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('PlusMoney',$PlusMoney);
		$this->assign('MinusMoney',$MinusMoney);
		$this->assign('BalanceMoney',$BalanceMoney);
		return $this->fetch();
	}

	/**
	 * [添加]
	 */
	public function add()
	{
		if(request()->isPost()){
			$post = input('post.');
			$res = MoneyService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = MoneyService::getInstance()->error;
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
			$res = MoneyService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = MoneyService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
				$this->code['data'] = "/article/index";
			}
			return $this->code;
		}
		if(empty($id)){
		 	$error = '参数不完整或者参数错误！';
        	$this->redirect("error/show/msg/{$error}");
		}else{
			$data = MoneyService::getInstance()->getOneById($id);
			if(empty($data)){
				$error = MoneyService::getInstance()->error;
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
		$res = MoneyService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = MoneyService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}