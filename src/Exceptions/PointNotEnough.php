<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午2:36
 */

namespace Panigale\Point\Exceptions;


use InvalidArgumentException;

class PointNotEnough extends InvalidArgumentException
{

    /**
     * rules already exists exception.
     *
     * @param string $roleName
     * @return static
     */
    public static function create()
    {
        $message = 'Point is not enough to use.';

        if(request()->wantsJson()){
            return response()->json([
                'code'    => 401,
                'message' => $message,
                'errors'  => $message
            ]);
        }

        return new static($message);
    }
}