<?php 
/**
 * 友情链接模型
 * Class Friendly
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace Models;

class Friendly extends Base
{

    /**
     * 必须声明此静态属性，单例模式下防止实例对象覆盖
     * @var null
     */
    protected static $instance = null;

	/**
     * 自动完成包含新增和更新
     * @var [type]
     */
    protected $auto = array();

    /**
     * 新增
     * @var array
     */
    protected $insert = array('add_time','update_time');

    /**
     * 更新
     * @var array
     */
    protected $update = array('update_time'); 

    /**
     * [setAddTimeAttr description]
     */
    protected function setAddTimeAttr(){
        return date('Y-m-d H:i:s',request()->time());
    } 

    /**
     * [setUpdateTimeAttr description]
     */
    protected function setUpdateTimeAttr(){
        return date('Y-m-d H:i:s',request()->time());
    } 

}