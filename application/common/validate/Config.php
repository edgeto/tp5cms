<?php 
/**
 * 配置验证器
 * Class Config
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Config extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'config_name' => 'require|unique:config',
        'config_sign' => 'require|unique:config',
        'config_value' => 'require',
    	'sort' => 'number',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'config_name.require' => '请输入名称',
        'config_name.unique' => '名称已存在',
        'config_sign.require' => '请输入标识',
        'config_sign.unique' => '标识已存在',
        'config_value.require' => '请输入配置值',
        'sort.number'  => '排序请输入纯数字',
    );

    /**
     * [$scene description]
     * @var array
     */
    protected $scene = array(
        'addGroup' => array('config_name','config_sign'),
        'editGroup' => array('config_name','config_sign'),
    );

}
