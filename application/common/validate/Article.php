<?php 
/**
 * 文章验证器
 * Class Article
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\common\validate;
use think\Validate;

class Article extends Validate
{
    
    /**
     * 规则
     * @var array
     */
    protected $rule = array(
        'title' => 'require',
        'content' => 'require',
    );

    /**
     * 提示
     * @var array
     */
    protected $message  = array(
        'title.require' => '请填写文章标题',
        'content.require' => '请填写文章内容',
    );

}
