<?php
/**
 * 资源数据处理
 * Class ResourceData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\Resource;

class ResourceData extends BaseData
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
    public $tablName = 'Resource';

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
     * 根据控制器和动作取资源id
     * @param  string $controller [description]
     * @param  string $action     [description]
     * @return [type]             [description]
     */
    public function getIdByControllerAction($controller = '',$action = '')
    {
        if(empty($controller) || empty($action)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Resource = new Resource();
        $where['controller'] = $controller;
        $where['action'] = $action;
        $data = $Resource->where($where)->find();
        if($data){
            return $data->id;
        }else{
            $this->error = '找不到资源！';
            return false;
        }
    }

    /**
     * [getNavByIdArr description]
     * @param  array  $ids [description]
     * @return [type]      [description]
     */
    public function getNavByIdArr($ids = array())
    {
        if(empty($ids)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        // if(is_string($ids)){
        //     $ids_map = "(" . $ids . ")";
        // }else{
        //     $ids_map = "(" . implode($ids,',') . ")";
        // }
        $Resource = new Resource();
        $where['id'] = array('in',$ids);
        $where['show_nav'] = 1;
        $where['channel_id'] = array('neq',0);
        $data = $Resource->where($where)->limit(1)->order('show_order desc')->field('route')->find();
        if($data){
            return $data['route'];
        }else{
            $this->error = '找不到资源！';
            return false;
        }
    }

    /**
     * [getByChannelId description]
     * @param  integer $channel_id [description]
     * @return [type]              [description]
     */
    public function getByChannelId($channel_id = 0)
    {
        $resource = new Resource();
        $where['channel_id'] = $channel_id;
        $data = $resource->where($where)->select();
        if($data){
            return $data;
        }else{
            $this->error = '找不到资源！';
            return false;
        }
    }

    /**
     * [getById description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function getById($id = 0)
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $resource = new Resource();
        $where['id'] = $id;
        $data = $resource->where($where)->find();
        if($data){
            return $data;
        }else{
            $this->error = "资源不存在！";
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
        $Resource = new Resource();
        $result = $Resource->allowField(true)->validate('Resource.addChannel')->save($data);
        if(empty($result)){
            $this->error = $Resource->getError();
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
        $Resource = new Resource();
        $res = $Resource->allowField(true)->validate('Resource.editChannel')->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $Resource->getError();
            return false;
        }
        return true;
    }

    /**
     * 取全部
     * @return [type] [description]
     */
    public function getAll()
    {
     $data = $this->Model->order('show_order desc')->select();
        if(!empty($data)){
            return $data;
        }else{
            $this->error = "数据不存在！";
            return false;
        }
    }

}