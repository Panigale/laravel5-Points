<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:25
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\PointIncrease;

trait AddPoint
{
    protected function addPointToUser(int $userId, int $pointId, $number, $beforePoint, $afterPoint)
    {
        $this->logPoint(new PointIncrease(), $pointId, $number, $beforePoint, $afterPoint);

        return PointIncrease::create([
            'number'       => $number,
            'point_id'     => $pointId,
            'user_id'      => $userId,
            'before_point' => $beforePoint,
            'after_point'  => $afterPoint
        ]);
    }
}