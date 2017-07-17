<?php 
/**
 * 广告位验证器
 * Class AdPosition
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class AdPosition extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'name' => 'require|unique:AdPosition',
        'sign' => 'require|unique:AdPosition',
        'width' => 'number',
        'height' => 'number',
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
        'width.number'  => '宽度请输入纯数字',
        'height.number'  => '高度请输入纯数字',
    );

}
