<?php
/**
 * 广告位数据处理
 * Class AdPositionData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\AdPosition;

class AdPositionData extends BaseData
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
    public $tablName = 'AdPosition';

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
     * [getById description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function getOneByMap($map = array())
    {
        if(empty($map)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $AdPosition = new AdPosition();
        $data = $AdPosition->where($map)->find();
        if($data){
            return $data;
        }else{
            $this->error = "广告位不存在！";
            return false;
        }
    }

}