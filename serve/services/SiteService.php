<?php 
/**
 * 站点业务处理器
 * Class SiteService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\SiteData;

class SiteService extends BaseService
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
    public $dataName = 'SiteData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_site_.log'; 

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
     * [getOneAdSiteById description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function getOneSiteById($id = 0)
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $map['id'] = $id;
        $adsite = SiteData::getInstance()->getOneAdSiteByMap($map);
        if($adsite){
            $adsite = $adsite->toArray();
            return $adsite;
        }else{
            $this->error = "广告站点不存在！";
            return false;
        }
    }

}