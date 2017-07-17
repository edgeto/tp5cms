<?php
/**
 * 系统配置数据处理
 * Class ConfigData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\Config;

class ConfigData extends BaseData
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
    public $tablName = 'Config';

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
     * [getByGroupId description]
     * @param  integer $group_id [description]
     * @return [type]              [description]
     */
    public function getByGroupId($group_id = 0)
    {
        $config = new Config();
        $where['group_id'] = $group_id;
        $data = $config->where($where)->select();
        if($data){
            return $data;
        }else{
            $this->error = '找不到资源！';
            return false;
        }
    }

    /**
     * [add description]
     * @param array $data [description]
     */
    public function addGroup($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Config = new Config();
        $result = $Config->allowField(true)->validate('Config.addGroup')->save($data);
        if(empty($result)){
            $this->error = $Config->getError();
            return false;
        }
        return true;
    }

    /**
     * [edit description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function editGroup($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Config = new Config();
        $res = $Config->allowField(true)->validate('Config.editGroup')->save($data,array('id'=>$data['id']));
        if(empty($res)){
            $this->error = $Config->getError();
            return false;
        }
        return true;
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
        $Config = new Config();
        $where['id'] = $id;
        $data = $Config->where($where)->find();
        if($data){
            return $data;
        }else{
            $this->error = "资源不存在！";
            return false;
        }
    }

}