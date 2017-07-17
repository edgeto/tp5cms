<?php 
/**
 * 友情链接验证器
 * Class Friendly
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Friendly extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require|unique:Friendly',
        'url' => 'require',
        'sort' => 'number',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
        'url.require' => '请输入链接',
        'sort.number'  => '排序请输入纯数字',
    );

}
