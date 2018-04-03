<?php
/**
 * Author: Panigale
 * Date: 2018/4/3
 * Time: 下午2:09
 */

namespace Panigale\Point\Exceptions;


use InvalidArgumentException;

class PointEventNotExist extends InvalidArgumentException
{
    /**
     * rules already exists exception.
     *
     * @param string $roleName
     * @return static
     */
    public static function create()
    {
        $message = 'Point event does not isset.';

        return new static($message);
    }
}