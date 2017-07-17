<?php 
/**
 * 管理员验证器
 * Class Admin
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Admin extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'username' => 'require|unique:admin',
        'password' => 'require',
        'repassword'=>'require|confirm:password'
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'username.require' => '请输入管理员名称',
        'username.unique' => '管理员名称已存在',
        'password.require'  => '请输入管理员密码',
        'repassword.require'  => '请输入管理员确认密码',
        'repassword.confirm'  => '管理员确认密码不正确',
    );

    /**
     * [$scene description]
     * @var array
     */
    protected $scene = array(
        'add' => array('username','password','repassword'),
        'edit' => array('username'),
        'password' => array('password','repassword'),
    );

}
