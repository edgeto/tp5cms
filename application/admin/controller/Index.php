<?php
/**
 * 后台首页
 * Class Index
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/11/12
 * Time: 11:00
 */
namespace app\admin\controller;

class Index extends Base
{

    /**
     * 后台首页
     * Function index
     * User: edgeto
     * Date: 2016/11/12
     * Time: 11:00
     * @return [type] [description]
     */
    public function index()
    {
        return $this->fetch();
    }

}
