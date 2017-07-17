<?php 
/**
 * 管理员业务处理器
 * Class AadminService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use Datas\AdminLogData;


class AdminLogService extends BaseService
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
    public $dataName = 'AdminLogData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = ''; 

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $Data = 'Datas\\'."$this->dataName";
        // $Data = new $Data;
        $this->Data = $Data::getInstance();
    }

}