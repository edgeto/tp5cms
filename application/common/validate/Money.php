<?php 
/**
 * 财务验证器
 * Class Money
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Money extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'username' => 'require',
        'money' => 'require',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'username.require' => '请输入姓名',
        'money.require' => '请输入金额',
    );

}
