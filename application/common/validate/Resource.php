<?php 
/**
 * 资源验证器
 * Class Resource
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Resource extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require|unique:resource',
        'nav_name' => 'require',
        'controller' => 'require',
    	'action' => 'require',
    	'sort' => 'number',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
        'controller.require' => '请输入控制器',
        'action.require' => '请输入动作',
        'sort.number'  => '排序请输入纯数字',
    );

    /**
     * [$scene description]
     * @var array
     */
    protected $scene = array(
        'addChannel' => array('name','nav_name'),
        'editChannel' => array('name','nav_name'),
    );

}
