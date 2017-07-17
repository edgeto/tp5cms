<?php 
/**
 * 友情链接业务处理器
 * Class FriendlyService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Config;
use Libs\Func;
use Datas\FriendlyData;

class FriendlyService extends BaseService
{

	/**
	 * 必须声明此静态属性，单例模式下防止实例对象覆盖
	 * @var null
	 */
    protected static $instance = null;

    /**
     * 表名
     * @var string
     */
    public $dataName = 'FriendlyData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_friendly_.log'; 

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $Data = 'Datas\\'."$this->dataName";
        // $Data = new $Data;
        $this->Data = $Data::getInstance();
    }

    /**
     * [add description]
     * @param array $data [description]
     */
    public function add($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if(!empty($_FILES)){
            // 图算上传
            $config = config('PICTURE_UPLOAD');
            $Func = new Func();
            $res = $Func->upload('img',$config);
            if(!$res){
                $this->error = $Func->error;
                return false;
            }else{
                $data['img'] = $res;
            }
        }
        $res = FriendlyData::getInstance()->add($data);
        if(empty($res)){
            $this->error = FriendlyData::getInstance()->error;
            return false;
        }
        $this->cache();
        return true;
    }

    /**
     * [edit description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function edit($data = array())
    {
        if(empty($data)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        if(!empty($_FILES)){
            // 图片上传
            $config = config('PICTURE_UPLOAD');
            $Func = new Func();
            $res = $Func->upload('img',$config);
            if(!$res){
                $this->error = $Func->error;
                return false;
            }else{
                $data['img'] = $res;
            }
        }else{
            // 不能更新图片
            unset($data['img']);
        }
        $res = FriendlyData::getInstance()->edit($data);
        if(empty($res)){
            $this->error = FriendlyData::getInstance()->error;
            return false;
        }
        $this->cache();
        return true;
    }

    /**
     * [getById description]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function getOneById($id = 0)
    {
        if(empty($id)){
            $this->error = '参数不完整或者参数错误！';
            return false;
        }
        $map['id'] = $id;
        $res = FriendlyData::getInstance()->getOneAdByMap($map);
        if(empty($res)){
            $this->error = FriendlyData::getInstance()->error;
            return false;
        }
        return $res;
    }

}