<?php 
/**
 * Services Base类
 * Class BaseService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Controller;
use Datas;

class BaseService extends Controller
{

    /**
     * [$instance description]
     * @var null
     */
    protected static $instance = null;

    /**
     * [$error description]
     * @var null
     */
	public $error = null;

    /**
     * 表名
     * @var string
     */
    public $dataName = '';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = ''; 

    /**
     * 单例
     * @return class
     */
    public static function getInstance()
    {
        if(empty(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * [delInstance description]
     * @return [type] [description]
     */
    public static function delInstance()
    {
        static::$instance = null;
    }

    /**
     * [add description]
     * @param array $data [description]
     */
    public function add($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $res = $this->Data->add($data);
        if(empty($res)){
            $this->error = $this->Data->error;
            return false;
        }
        if($this->cacheKey){
            $this->cache();
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
        $res = $this->Data->edit($data);
        if(empty($res)){
            $this->error = $this->Data->error;
            return false;
        }
        if($this->cacheKey){
            $this->cache();
        }
        return true;
    }

    /**
     * [del description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function del($id = 0)
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $res = $this->Data->del($id);
        if(empty($res)){
            $this->error = $this->Data->error;
            return false;
        }
        if($this->cacheKey){
            $this->cache();
        }
        return true;
    }

    /**
     * 更新缓存
     * @return [type] [description]
     */
    public function cache()
    {
        $cacheKey = $this->cacheKey;
        $res = $this->Data->getAll();
        if($res){
            foreach ($res as $key => $value) {
                if(is_object($value)){
                    $value = $value->getData();
                }
                $_res[$value['id']] = $value;
            }
            cache($cacheKey,$_res);
        }else{
            cache($cacheKey,null);
        }
    }

    /**
     * 拿缓存
     * @return [type] [description]
     */
    public function getCache()
    {
        $cacheKey = $this->cacheKey;
        $res = cache($cacheKey);
        if(empty($res)){
            $this->cache();
            $res = cache($cacheKey);
        }
        return $res;
    }

    /**
     * 分页数据
     * [getPage description]
     * @param  array   $map      [description]
     * @param  integer $pageSize [description]
     * @return [type]            [description]
     */
    public function getPage($map = array(),$pageSize = 10)
    {
        $res = $this->Data->getPage($map,$pageSize);
        if(empty($res)){
            $this->error = '没有数据！';
            return false;
        }
        return $res;
    }

    /**
     * [count description]
     * @param  array  $map [description]
     * @return [type]      [description]
     */
    public function count($map = array())
    {
        $res = $this->Data->count($map);
        if(empty($res)){
            $this->error = $this->Data->error;
            return false;
        }
        return $res;
    }

    
}