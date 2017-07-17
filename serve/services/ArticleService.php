<?php 
/**
 * 文章业务处理器
 * Class ArticleService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Config;
use Datas\ArticleData;
use Libs\Func;

class ArticleService extends BaseService
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
    public $dataName = 'ArticleData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = ''; 

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
        $res = ArticleData::getInstance()->add($data);
        if(empty($res)){
            $this->error = ArticleData::getInstance()->error;
            return false;
        }
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
        $res = ArticleData::getInstance()->edit($data);
        if(empty($res)){
            $this->error = ArticleData::getInstance()->error;
            return false;
        }
        return true;
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
        $article = ArticleData::getInstance()->getArticleById($article_id);
        if($article){
            return $article;
        }else{
            $this->error = "文章不存在！";
            return false;
        }
    }

    /**
     * 按月份查找数据
     * [getListByMonth description]
     * @param  string   $year      [description]
     * @param  string   $month      [description]
     * @param  string   $day      [description]
     * @return [type]            [description]
     */
    public function getListByMonth($year = '',$month = '' ,$day = '')
    {
        $list = array();
        if(empty($year) || empty($month)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if($day){
            // 日事件列表
            if($month < 10){
                // js 没有传前导0过来
               $month = '0'.$month;
            }
            if($day < 10){
                // js 没有传前导0过来
               $day = '0'.$day;
            }
            $time = $year . $month . $day;
            $day = $time;
            $netx_day = date('Y-m-d',strtotime("+1 day",strtotime($day)));
            $map['on_time'] = array(array('egt',$day),array('lt',$netx_day));
        }else{
            // 月事件列表
            if($month < 10){
                // js 没有传前导0过来
               $month = '0'.$month;
            }
            $time = $year . $month .'01';
            $month_time = strtotime($time);
            // 查询月一号
            $current_month = date('Y-m',$month_time);
            // 查询月下一月
            $next_month = date('Y-m',strtotime("+1 month",$month_time));
            $map['on_time'] = array(array('gt',$current_month),array('lt',$next_month));
        }
        $map['status'] = 0;
        $res = ArticleData::getInstance()->getPage($map,100);
        if(empty($res) || empty($res['list'])){
            $this->error = '没有数据！';
            return false;
        }else{
            $list = $res['list'];
            $static_url = config::get('domain.static','');
            foreach ($list as $key => &$value) {
                if($value['img']){
                    $value['img'] = $static_url . $value['img'];
                }
                $value['on_time'] = date('Y-m-d',strtotime($value['on_time']));
                $value['year'] = date('Y',strtotime($value['on_time']));
                $value['month'] = date('n',strtotime($value['on_time']));
                $value['day'] = date('j',strtotime($value['on_time']));
            }
        }
        return $list;
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
        $article = ArticleData::getInstance()->getOneArticleByMap($map);
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