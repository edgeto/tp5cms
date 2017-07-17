<?php 
/**
 * 站点验证器
 * Class Site
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Site extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'name.require' => '请填写站点名称',
    );

}
