<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
if(ENVIRONMENR == 'DEVELOPMENT') {
    return [
        '__pattern__' => [
            'name' => '\w+',
        ],
        '[hello]'     => [
            ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
            ':name' => ['index/hello', ['method' => 'post']],
        ],
        // 域名路由
        '__domain__' => [
//            'admin.cms.ngx' => 'admin',
//            'www.cms.ngx' => 'home',
            'cmsadmin.yangsoon.cn:8099' => 'admin',
            'cms.yangsoon.cn:8099' => 'home',
        ],
    ];
}else if(ENVIRONMENR == 'TESTING'){

}else if(ENVIRONMENR == 'STAGING'){

}else if(ENVIRONMENR == 'PRODUCTION'){
    return [
        '__pattern__' => [
            'name' => '\w+',
        ],
        '[hello]'     => [
            ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
            ':name' => ['index/hello', ['method' => 'post']],
        ],
        // 域名路由
        '__domain__' => [
            'cmsadmin.580vps.com' => 'admin',
            'cms.580vps.com' => 'home',
        ],
    ];
}
