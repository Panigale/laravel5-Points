<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:24
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\Point;
use Panigale\Point\Models\PointUsage;

trait UsagePoint
{
    protected function usagePointToUser(int $pointId, $number, $beforePoint, $afterPoint)
    {
        $this->logPoint($pointId , $number, $beforePoint, $afterPoint);

        $point = Point::lockForUpdate()->find($pointId);
        $point->number = $afterPoint;
        $point->save();

        return $point;
    }
}
