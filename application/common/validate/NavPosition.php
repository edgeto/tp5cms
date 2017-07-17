<?php 
/**
 * 导航位验证器
 * Class NavPosition
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class NavPosition extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require|unique:NavPosition',
        'sign' => 'require|unique:NavPosition',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
        'sign.require' => '请输入标识',
        'sign.unique' => '标识已存在',
    );

}
