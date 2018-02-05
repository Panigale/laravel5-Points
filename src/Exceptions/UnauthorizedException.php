<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:26
 */

namespace Panigale\Point\Exceptions;




use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    public static function forUsagePoint()
    {
        $message = 'User does not authorized.';

        $exception = new static(403, $message, null, []);

        return $exception;
    }
}