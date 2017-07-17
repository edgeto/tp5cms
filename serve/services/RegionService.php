<?php 
/**
 * 区或业务处理器
 * Class RegionService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\RegionData;

class RegionService extends BaseService
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
    public $dataName = 'RegionData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_region_.log'; 

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
     * [getById description]
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
        $res = RegionData::getInstance()->getOneAdByMap($map);
        if(empty($res)){
            $this->error = RegionData::getInstance()->error;
            return false;
        }
        return $res;
    }

}