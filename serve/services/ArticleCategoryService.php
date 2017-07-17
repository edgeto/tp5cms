<?php 
/**
 * 文章分类业务处理器
 * Class ArticleCategoryService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Config;
use Datas\ArticleCategoryData;
use Libs\Func;

class ArticleCategoryService extends BaseService
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
    public $dataName = 'ArticleCategoryData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_article_category_.log'; 

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $Data = 'Datas\\'."$this->dataName";
        // $Data = new $Data;
        $this->Data = $Data::getInstance();
    }

    /**
     * 添加
     * @param array $data [description]
     */
    public function add($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if(!empty($_FILES)){
            // 图片上传
            $config = config('PICTURE_UPLOAD');
            $Func = new Func();
            $res = $Func->upload('img',$config);
            if(!$res){
                $this->error = $Func->error;
                return false;
            }else{
                $data['img'] = $res;
            }
        }
        $res = ArticleCategoryData::getInstance()->add($data);
        if(empty($res)){
            $this->error = ArticleCategoryData::getInstance()->error;
            return false;
        }
        $this->cache();
        return true;
    }

    /**
     * [edit description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function edit($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if(!empty($_FILES)){
            // 图片上传
            $config = config('PICTURE_UPLOAD');
            $Func = new Func();
            $res = $Func->upload('img',$config);
            if(!$res){
                $this->error = $Func->error;
                return false;
            }else{
                $data['img'] = $res;
            }
        }else{
            // 不能更新图片
            unset($data['img']);
        }
        $res = ArticleCategoryData::getInstance()->edit($data);
        if(empty($res)){
            $this->error = ArticleCategoryData::getInstance()->error;
            return false;
        }
        $this->cache();
        return true;
    }

    /**
     * [getOnearticle_categoryById description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function getOneById($id = 0)
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $map['id'] = $id;
        $article_category = ArticleCategoryData::getInstance()->getOneByMap($map);
        if($article_category){
            $article_category = $article_category->toArray();
            return $article_category;
        }else{
            $this->error = "广告站点不存在！";
            return false;
        }
    }

    /**
     * 通过条件取记录
     * @param  [type] $map [description]
     * @return [type]           [description]
     */
    public function getArticleDetail($id = '')
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $map['id'] = $id;
        $map['status'] = 0;
        $article = ArticleCategoryData::getInstance()->getOneArticleByMap($map);
        if($article){
            $article = $article->toArray();
            if($article['img']){
                $static_url = config::get('domain.static','');
                $article['img'] = $static_url . $article['img'];
            }
            return $article;
        }else{
            $this->error = "文章不存在！";
            return false;
        }
    }

}