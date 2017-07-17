<?php
/**
 * 文章分类数据处理
 * Class ArticleCategoryData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\ArticleCategory;
use Libs\Func;

class ArticleCategoryData extends BaseData
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
    public $tablName = 'ArticleCategory';

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
     * 通过条件取记录
     * @param  [type] $map [description]
     * @return [type]           [description]
     */
    public function getOneByMap($map = array())
    {
        if(empty($map)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $ArticleCategory = new ArticleCategory();
        $article_category_info= $ArticleCategory->where($map)->find();
        if($article_category_info){
            return $article_category_info;
        }else{
            $this->error = "文章分类不存在！";
            return false;
        }
    }

}