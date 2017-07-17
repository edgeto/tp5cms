<?php 
/**
 * 资源业务处理器
 * Class ResourceService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\ResourceData;

class ResourceService extends BaseService
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
    public $dataName = 'ResourceData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_admin_resource.log'; 

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
     * 取菜单(二级导航)
     * @param  integer $is_super [description]
     * @param  array   $rules    [description]
     * @return [type]            [description]
     */
    public function getMenu($is_super = 0,$rules = array())
    {
    	$data = array();
    	$cacheKey = '_cms_admin_resource.log';
        $resource = cache($cacheKey);
        if(empty($resource)){
        	$this->cache();
            $resource = cache($cacheKey);
        }
        // 不是超管理员
        if(!$is_super){
            $resource = array_filter($resource,function($var)use($rules){
                if(!isset($rules[$var['id']])){
                    return false;
                }
                return true;
            });
        }
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        $breadCrumbs = array();
        foreach ($resource as $key => &$value) {
        	if(is_object($value)){
        		$value = $value->getData();
        	}
            if(!isset($value['current'])){
                $value['current'] = 0;
            }
            if($controller == strtolower($value['controller']) && $action == strtolower($value['action'])){
                $value['current'] = 1;
            }
            if (isset($resource[$value['channel_id']])) {
                if($value['current']){
                    $resource[$value['channel_id']]['current'] = $value['current'];
                    $breadCrumbs[] = $resource[$value['channel_id']];
                    $breadCrumbs[] = $value;
                }
                if($value['show_nav'] == 1){
                    $resource[$value['channel_id']]['son'][] =  $value;
                }
                unset($resource[$key]);
            }
        }
        if($resource){
            $data['leftMenu'] = $resource;
            $data['breadCrumbs'] = $breadCrumbs;
        }
        return $data;
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
        $route = ResourceData::getInstance()->getNavByIdArr($ids);
        if(empty($route)){
            $this->error = ResourceData::getInstance()->error;
            return false;
        }
        return $route;
    }

    /**
     * [getByGroupId description]
     * @param  integer $channel_id [description]
     * @return [type]              [description]
     */
    public function getByChannelId($channel_id = 0)
    {
        $resource = ResourceData::getInstance()->getByChannelId($channel_id);
        if(empty($resource)){
            $this->error = ResourceData::getInstance()->error;
            return false;
        }else{
            foreach ($resource as $key => &$value) {
                if(is_object($value)){
                    $value = $value->getData();
                }
            }
        }
        return $resource;
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
        $res = ResourceData::getInstance()->getById($id);
        if(empty($res)){
            $this->error = ResourceData::getInstance()->error;
            return false;
        }
        return $res;
    }

    /**
     * [getChannelCache description]
     * @return [type] [description]
     */
    public function getChannelCache()
    {
       $data = array();
       $cache = $this->getCache();
       if($cache){
            foreach ($cache as $key => $value) {
               if($value['channel_id'] == 0){
                  $data[$value['id']] = $value; 
               }
            }
       } 
       return $data;
    }

}