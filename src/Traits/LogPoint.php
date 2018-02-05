<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午1:14
 */

namespace Panigale\Point\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogPoint
{
    public function logPoint(Model $model, int $pointId, $point, $beforePoint, $afterPoint)
    {
        $model::create([
            'user_id'      => Auth::id(),
            'before_point' => $beforePoint,
            'point_id'     => $pointId,
            'number'       => $point,
            'after_number' => $afterPoint
        ]);
    }
}