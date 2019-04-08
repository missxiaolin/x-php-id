<?php
/**
 * Created by PhpStorm.
 * User: gb
 * Date: 2019-02-22
 * Time: 19:02
 */

namespace Lin\Snowflake;

use Lin\Snowflake\Exceptions\SnowflakeException;

abstract class Client implements ClientInterface
{
    protected static $_instance;

    /** @var  integer 毫秒 */
    protected $beginAt;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (isset(static::$_instance) && static::$_instance instanceof ClientInterface) {
            return static::$_instance;
        }

        return static::$_instance = new static();
    }

    /**
     * 返回起始时间 秒
     * @return false|int
     */
    public function getBeginAt()
    {
        if (isset($this->beginAt) && is_numeric($this->beginAt)) {
            return $this->beginAt;
        }

        return strtotime('2017-01-01');
    }

    /**
     * 获取唯一ID的方法
     * @param $workerId
     * @param $sequenceId
     * @param null $currentTime
     * @return float|int
     * @throws SnowflakeException
     */
    public function id($workerId, $sequenceId, $currentTime = null)
    {
        if ($workerId < 0 || $workerId > 1023 || !is_int($workerId)) {
            throw new SnowflakeException('workerId 只能在 0-1023 之间的整数');
        }

        if ($sequenceId < 0 || $sequenceId > 4095 || !is_int($sequenceId)) {
            throw new SnowflakeException('sequenceId 只能在 0-4095 之间的整数');
        }

        $time = $this->getBeginAt();

        if (!isset($currentTime)) {
            $currentTime = time();
        }

        $bit1 = str_pad(decbin($currentTime - $time), 41, '0', STR_PAD_LEFT);
        $bit2 = str_pad(decbin($workerId), 10, '0', STR_PAD_LEFT);
        $bit3 = str_pad(decbin($sequenceId), 12, '0', STR_PAD_LEFT);

        return bindec('0' . $bit1 . $bit2 . $bit3);
    }
}