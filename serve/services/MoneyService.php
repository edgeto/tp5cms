<?php 
/**
 * 财务业务处理器
 * Class MoneyService
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Services;
use think\Config;
use Datas\MoneyData;
use Libs\Func;

class MoneyService extends BaseService
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
    public $dataName = 'MoneyData';

    /**
     * 缓存key--有则更新缓存
     * @var boolean
     */
    public $cacheKey = '_cms_money_.log'; 

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $Data = 'Datas\\'."$this->dataName";
        // $Data = new $Data;
        $this->Data = $Data::getInstance();
    }

    public function getSumMoneyByPlus()
    {
        $money = 0;
        $map['type'] = 0;
        $money = MoneyData::getInstance()->getSumMoneyByMap($map);
        return $money;
    }

    public function getSumMoneyByMinus()
    {
        $money = 0;
        $map['type'] = 1;
        $money = MoneyData::getInstance()->getSumMoneyByMap($map);
        return $money;
    }

    /**
     * [getOnearticle_categoryById description]
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
        $money = MoneyData::getInstance()->getOneByMap($map);
        if($money){
            $money = $money->toArray();
            return $money;
        }else{
            $this->error = "财务信息不存在！";
            return false;
        }
    }

}