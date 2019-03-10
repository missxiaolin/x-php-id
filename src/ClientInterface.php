<?php
/**
 * Created by PhpStorm.
 * User: gb
 * Date: 2019-02-22
 * Time: 19:02
 */

namespace Lin\Snowflake;

/**
 * Interface ClientInterface
 * @package Lin\Snowflake
 */
interface ClientInterface
{
    public static function getInstance();

    public function getBeginAt();
}