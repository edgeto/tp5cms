<?php 
/**
 * 配置处理器
 * Class ConfigService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\ConfigData;
use think\Config;

class ConfigService extends BaseService
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
    public $dataName = 'ConfigData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_config.log'; 

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
     * [getChannelCache description]
     * @return [type] [description]
     */
    public function getGroupCache()
    {
       $data = array();
       $cache = $this->getCache();
       if($cache){
            foreach ($cache as $key => $value) {
               if($value['group_id'] == 0){
                  $data[$value['id']] = $value; 
               }
            }
       } 
       return $data;
    }
	/**
     * [getSignCache description]
     * @return [type] [description]
     */
    public function getSignCache()
    {
        $data = array();
        $webConfig = $this->getCache();
        if($webConfig){
            foreach ($webConfig as $key => $value) {
                if(!empty($value['config_sign'])){
                    $_webConfig[$value['config_sign']] = $value;
                }
            }
            $data = $_webConfig;
        }
        return $data;
    }

    /**
     * [getByGroupId description]
     * @param  integer $group_id [description]
     * @return [type]              [description]
     */
    public function getByGroupId($group_id = 0)
    {
        $config = ConfigData::getInstance()->getByGroupId($group_id);
        if(empty($config)){
            $this->error = ConfigData::getInstance()->error;
            return false;
        }
        return $config;
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
        $res = ConfigData::getInstance()->addGroup($data);
        if(empty($res)){
            $this->error = ConfigData::getInstance()->error;
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
    public function editGroup($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $res = ConfigData::getInstance()->editGroup($data);
        if(empty($res)){
            $this->error = ConfigData::getInstance()->error;
            return false;
        }
        $this->cache();
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
        $res = ConfigData::getInstance()->getById($id);
        if(empty($res)){
            $this->error = ConfigData::getInstance()->error;
            return false;
        }
        return $res;
    }

}