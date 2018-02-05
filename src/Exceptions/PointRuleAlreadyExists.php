<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:39
 */

namespace Panigale\Point\Exceptions;


use InvalidArgumentException;

class PointRuleAlreadyExists extends InvalidArgumentException
{
    /**
     * rules already exists exception.
     *
     * @param string $roleName
     * @return static
     */
    public static function create(string $roleName)
    {
        return new static("A point role `{$roleName}` already exists.");
    }
}