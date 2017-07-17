<?php
/**
 * 站点数据处理
 * Class SiteData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\Site;

class SiteData extends BaseData
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
    public $tablName = 'Site';

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
    public function getOneSiteByMap($map = array())
    {
        if(empty($map)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $Site = new Site();
        $Site_info= $Site->where($map)->find();
        if($Site_info){
            return $Site_info;
        }else{
            $this->error = "广告站点不存在！";
            return false;
        }
    }

}