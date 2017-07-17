<?php
/**
 * 文章
 * Class Article
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;
use Services\ArticleService;
use Services\ArticleCategoryService;

class Article extends Base
{

	/**
	 * 文章列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$map = array();
		$res = ArticleService::getInstance()->getPage($map,$this->pageSize);
		$count = 0;
		$list = $page = array();
		if($res){
			$count = $res['count'];
			$list = $res['list'];
			$page = $res['page'];
		}
		$cache = ArticleCategoryService::getInstance()->getCache();
		$this->assign('article_category_list',$cache);
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
			$res = ArticleService::getInstance()->add($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ArticleService::getInstance()->error;
			}else{
				$this->code['code'] = 200;
				$this->code['msg'] = '成功';
			}
			return $this->code;
		}
		$cache = ArticleCategoryService::getInstance()->getCache();
		$this->assign('article_category_list',$cache);
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
			$res = ArticleService::getInstance()->edit($post);
			if(empty($res)){
				$this->code['msg'] = '失败';
				$this->code['data'] = ArticleService::getInstance()->error;
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
			$data = ArticleService::getInstance()->getArticleById($id);
			if(empty($data)){
				$error = ArticleService::getInstance()->error;
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
		$res = ArticleService::getInstance()->del($id);
		if(empty($res)){
			$this->code['msg'] = '失败';
			$this->code['data'] = ArticleService::getInstance()->error;
		}else{
			$this->code['code'] = 200;
			$this->code['msg'] = '成功';
		}
		return $this->code;
	}

}