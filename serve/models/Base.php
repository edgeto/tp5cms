<?php 
/**
 * Base模型
 * Class Base
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace Models;
use think\Model;

class Base extends Model
{

	/**
     * [$instance description]
     * @var null
     */
    protected static $instance = null;

	/**
     * 单例
     * @return class
     */
    public static function getInstance()
    {
        if(empty(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * 取消
     * @return [type] [description]
     */
    public static function delInstance()
    {
        static::$instance = null;
    }

}