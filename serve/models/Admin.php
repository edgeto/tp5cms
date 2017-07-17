<?php 
/**
 * 管理员模型
 * Class Admin
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace Models;
use think\Model;
use Libs\Func;

class Admin extends Base
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
    protected $insert = array('password','add_time','update_time');

    /**
     * 更新
     * @var array
     */
    protected $update = array('update_time'); 

    /**
     * [setPasswordAttr description]
     */
    protected function setPasswordAttr($value){
        $Func = new Func();
        return $Func->adminMd5($value);
    } 

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