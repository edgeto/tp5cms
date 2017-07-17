<?php
/**
 * Library 基类
 * Class Base
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Libs;

class Base
{

	/**
	 * [$instance description]
	 * @var null
	 */
    protected static $instance = null;

    /**
     * [$error description]
     * @var null
     */
    public $error = null;

    /**
     * 单例
     * @return [type] [description]
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
    public function delInstance()
    {
        static::$instance = null;
    }

}