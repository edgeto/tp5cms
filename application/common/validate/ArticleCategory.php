<?php 
/**
 * 文章分类验证器
 * Class ArticleCategory
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class ArticleCategory extends Validate
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
        'name.require' => '请填写分类名称',
    );

}
