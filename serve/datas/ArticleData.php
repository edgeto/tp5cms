<?php
/**
 * 文章数据处理
 * Class ArticleData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\Article;
use Libs\Func;

class ArticleData extends BaseData
{

	/**
	 * 必须声明此静态属性，单例模式下防止实例对象覆盖
	 * @var null
	 */
    protected static $instance = null;

    /**
     * 表名
     * @var string
     */
    public $tablName = 'Article';

    /**
     * 初始化
     */
    public function __construct()
    {
        $Model = 'Models\\'."$this->tablName";
        // $Model = new $Model;
        $this->Model = $Model::getInstance();
    }

    /**
     * 通过文章id取记录
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
     */
    public function getArticleById($article_id = null)
    {
        if(empty($article_id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $article = new Article();
        $where['id'] = $article_id;
        $article_info= $article->where($where)->find();
        if($article_info){
            return $article_info;
        }else{
            $this->error = "文章不存在！";
            return false;
        }
    }

    /**
     * 通过条件取记录
     * @param  [type] $map [description]
     * @return [type]           [description]
     */
    public function getOneArticleByMap($map = array())
    {
        if(empty($map)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $article = new Article();
        $article_info= $article->where($map)->find();
        if($article_info){
            return $article_info;
        }else{
            $this->error = "文章不存在！";
            return false;
        }
    }

}