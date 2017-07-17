<?php
/**
 * 已重写redis类，使用igbinary对数组进行序列化
 * Class CacheRedis
 * Author: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Libs;
use think\Config;
use Libs\Base;

class CacheRedis
{

    /**
     * [$options description]
     * @var [type]
     */
    protected $options;
    /**
     * @var \Redis
     */
    protected $_redis = null;
    
    /**
     * @param unknown $options
     */
    public function __construct($options = array())
    {
        if(empty($options)){
            $options = Config::get('redis');
            if(isset($options['main'])){
                $options = $options['main'];
            }
        }
        $this->options = $options;
        $this->_connect();
    }

    /**
     * 重写连接
     */
    public function _connect()
    {
        if (empty($this->_redis)) {
            try {
                $this->_redis = new \Redis();
                if ($this->options['persistent']) {
                    $this->_redis->pconnect($this->options['host'], $this->options['port']);
                } else {
                    $this->_redis->connect($this->options['host'], $this->options['port']);
                }
                if(isset($this->options['auth']) && !empty($this->options['auth'])) {
                    $this->_redis->auth($this->options['auth']);
                }
                // SERIALIZER_IGBINARY需要安装php的igbinary扩展
                $this->_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }
    
    /**
     * @param int $db
     * @param number $db
     */
    public function selectDb($db = 0)
    {
        return $this->_redis->select($db);
    }
    
    /**
     * @param $key
     * @param $value=string|[]
     * @param $expireTime
     * @return bool
     * 设置key与值
     */
    public function setValue($key,$value,$expireTime=0)
    {
        if(empty($expireTime)) {
            return $this->_redis->set($key, $value);
        }else{
            return $this->_redis->set($key,$value,$expireTime);
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->_redis->get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        return $this->_redis->delete($key);
    }

    /**
     * @param $key
     */
    public function setKeyExpireTime($key,$expireTime)
    {
        return $this->_redis->setTimeout($key,$expireTime);
    }
    public function expire($key,$ttl)
    {
        return $this->_redis->expire($key,$ttl);
    }

    /**
     * @param $key
     * @param int $value
     * @return mixed
     */
    public function incre($key,$value=1)
    {
        return $this->_redis->incrBy($key,$value);
    }

    /**
     * @param $key
     * @param int $value
     * @return mixed
     */
    public function decre($key,$value=1)
    {
        return $this->_redis->decrBy($key,$value);
    }

    /**
     * @param $dbNode
     * 刷新指定的库
     */
    public function flushDb($dbNode)
    {
        if(!empty($dbNode)) {
            $this->_redis->select($dbNode);
            return $this->_redis->flushDb();
        }
    }

    /**
     * @param string $key
     * @param number $sort
     * @param string $value
     * @return []
     * @return bool
     */
    public function zAdd($key,$sort,$value)
    {
        return $this->_redis->zAdd($key,$sort,$value);
    }

    /**
     * @param string $key
     * @param int $offset
     * @param int $limit
     * @return []
     */
    public function zRange($key,$offset,$limit)
    {
        return $this->_redis->zRange($key,$offset,$limit);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function zDelete($key,$value)
    {
        return $this->_redis->zDelete($key,$value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function rPush($key,$value)
    {
        return $this->_redis->rPush($key,$value);
    }

    /**
     * @param $channel
     * @param $message
     * @return mixed
     */
    public function publish($channel, $message)
    {
        return $this->_redis->publish($channel, $message);
    }

    /**
     * @param $channel_patterns
     * @param $callback
     * @return mixed
     */
    public function psubscribe($channel_patterns,$callback){
        return $this->_redis->psubscribe($channel_patterns,$callback);
    }

    /**
     * @param $channel
     * @param $callback
     * @return mixed
     */
    public function subscribe($channel,$callback)
    {
        return $this->_redis->subscribe($channel,$callback);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function lPop($key)
    {
        return $this->_redis->lPop($key);
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return mixed
     */
    public function lRange($key, $start, $end)
    {
        return $this->_redis->lRange($key, $start, $end);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function lLen($key)
    {
        return $this->_redis->lLen($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function watch($key)
    {
        return $this->_redis->WATCH($key);
    }

    /**
     * @return mixed
     */
    public function multi()
    {
        return $this->_redis->MULTI();
    }

    /**
     * @return mixed
     */
    public function exec(){
        return $this->_redis->EXEC();
    }

    /**
     * @return mixed
     */
    public function discard()
    {
        return $this->_redis->DISCARD();
    }

    /**
     * Redis Hset 命令用于为哈希表中的字段赋值 。
     * 如果哈希表不存在，一个新的哈希表被创建并进行 HSET 操作。
     * 如果字段已经存在于哈希表中，旧值将被覆盖。
     * @param $key
     * @param $hashKey
     * @param $value
     * @return int
     */
    public function hSet($key,$hashKey,$value)
    {
        return $this->_redis->hSet($key,$hashKey,$value);
    }
    /**
     * HRedis Hmset 命令用于同时将多个 field-value (字段-值)对设置到哈希表中。
     * 此命令会覆盖哈希表中已存在的字段。
     * 如果哈希表不存在，会创建一个空哈希表，并执行 HMSET 操作。
     * @param $key
     * @param array $hashKeys
     * @return bool
     */
    public function hMset($key,array $hashKeys)
    {
        return $this->_redis->hMset($key,$hashKeys);
    }

    /**
     * 返回哈希表 key 中，一个或多个给定域的值。
     * 如果给定的域不存在于哈希表，那么返回一个 nil 值。
     * 因为不存在的 key 被当作一个空哈希表来处理，所以对一个不存在的 key 进行 HMGET 操作将返回一个只带有 nil 值的表。
     * @param $key
     * @param array $hashKeys
     * @return array
     */
    public function hMget($key,array $hashKeys)
    {
        return $this->_redis->hMGet($key,$hashKeys);
    }
    /**
     * Redis Hdel 命令用于删除哈希表 key 中的一个或多个指定字段，不存在的字段将被忽略。
     *
     * @param $key
     * @param $hashKey
     * @return int
     */
    public function hDel($key, $hashKey)
    {
        return $this->_redis->hDel($key,$hashKey);
    }

    /**
     * Redis Hget 命令用于返回哈希表中指定字段的值。
     * @param $key
     * @param $hashKey
     * @return string
     */
    public function hGet($key, $hashKey)
    {
        return $this->_redis->hGet($key,$hashKey);
    }

    /**
     * Redis Hsetnx 命令用于为哈希表中不存在的的字段赋值 。
     * 如果哈希表不存在，一个新的哈希表被创建并进行 HSET 操作。
     * 如果字段已经存在于哈希表中，操作无效。
     * 如果 key 不存在，一个新哈希表被创建并执行 HSETNX 命令。
     * @param $key
     * @param $hashKey
     * @param $value
     * @return bool
     */
    public function hSetnx($key, $hashKey, $value)
    {
        return $this->_redis->hSetNx($key, $hashKey,$value);
    }

    /**
     * Redis Hexists 命令用于查看哈希表的指定字段是否存在。
     * @param $key
     * @param $hashKey
     * @return bool
     */
    public function hExists ($key, $hashKey)
    {
        return $this->_redis->hExists($key, $hashKey);
    }
}