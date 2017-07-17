<?php 
/**
 * 财务模型
 * Class Ad
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace Models;

class Money extends Base
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
    protected $insert = array('add_time','update_time','use_time','money');

    /**
     * 更新
     * @var array
     */
    protected $update = array('update_time','use_time','money'); 

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

    /**
     * [setOnTimeAttr description]
     */
    protected function setUseTimeAttr(){
        $data = input('post.');
        if(isset($data['use_time']) && empty($data['use_time'])){
            return date('Y-m-d',request()->time());
        }else{
            return $data['use_time'];
        }
    } 

     /**
     * [setOnTimeAttr description]
     */
    protected function setMoneyAttr(){
        $data = input('post.');
        if(isset($data['type']) && $data['type'] == 1){
            // 支出的改为负数
            $data['money'] = isset($data['money']) ? -$data['money'] : 0;
        }else{
            $data['money'] = isset($data['money']) ? $data['money'] : 0;
        }
        return $data['money'];
    } 

}