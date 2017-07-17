<?php 
/**
 * 角色验证器
 * Class AdminRole
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class AdminRole extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require|unique:AdminRole',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
    );

}
