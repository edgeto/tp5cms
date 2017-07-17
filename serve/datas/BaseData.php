<?php 
/**
 * Datas Base类
 * Class BaseService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models;

class BaseData
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
    public $tablName = '';

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
     * 取消
     * @return [type] [description]
     */
    public static function delInstance()
    {
        static::$instance = null;
    }
    
    /**
     * 分页数据
     * @param  array   $map      [description]
     * @param  integer $pageSize [description]
     * @return [type]            [description]
     */
    public function getPage($map = array(),$pageSize = 10)
    {
        $data = array();
        $count = $this->count($map);
        $res = $this->Model->where($map)->order('id','desc')->paginate($pageSize,$count);
        if(empty($res)){
            $this->error = '没有数据！';
            return false;
        }
        $page = $res->render();
        $list = array();
        $data['count'] = $count;
        foreach ($res as $key => $value) {
            if(is_object($value)){
                $value = $value->getData();
            }
            $list[] = $value;
        }
        $data['list'] = $list;
        $data['page'] = $page;
        return $data;
    }

    /**
     * [count description]
     * @param  array  $map [description]
     * @return [type]      [description]
     */
    public function count($map = array())
    {
        $count = $this->Model->where($map)->count();
        return $count;
    }

    /**
     * 取全部
     * @return [type] [description]
     */
    public function getAll()
    {
     $data = $this->Model->order('id desc')->select();
        if(!empty($data)){
            return $data;
        }else{
            $this->error = "数据不存在！";
            return false;
        }
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
        $result = $this->Model->allowField(true)->validate(true)->save($data);
        if(empty($result)){
            $this->error = $this->Model->getError();
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
        $res = $this->Model->allowField(true)->validate(true)->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $this->Model->getError();
            return false;
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
        $where['id'] = intval($id);
        $res = $this->Model->where($where)->delete();
        if($res){
            return true;
        }else{
            $this->error = $this->Model->getError();
            return false;
        }
    }
    
}