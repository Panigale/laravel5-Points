<?php
/**
 * Author: Panigale
 * Date: 2018/1/26
 * Time: 下午3:14
 */

namespace Panigale\Point\Exceptions;


use InvalidArgumentException;

class PointRuleNotExist extends InvalidArgumentException
{
    /**
     * rules already exists exception.
     *
     * @param string $roleName
     * @return static
     */
    public static function create(string $roleName)
    {
        return new static("A point role `{$roleName}` does not exists.");
    }
}