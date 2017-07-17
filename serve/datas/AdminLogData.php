<?php
/**
 * 管理员登录日志数据处理
 * Class AdminLogData
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Datas;
use Models\AdminLog;
use Libs\Func;

class AdminLogData extends BaseData
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
    public $tablName = 'AdminLog';

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
	 * 添加记录
	 * @param string $data [description]
	 */
	public function addLog($data = '')
	{
		if($data){
			$Func = new Func();
			$AdminLog = new AdminLog();
			$admin_log['user_id'] = $data['id'];
			$admin_log['login_time'] = date("Y-m-d H:i:s");
			$admin_log['login_ip'] = $Func->getClientIp(0,1);
			$AdminLog->insertGetId($admin_log);
		}
	}

}