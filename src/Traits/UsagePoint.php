<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:24
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\PointUsage;

trait UsagePoint
{
    protected function usagePointToUser(int $userId ,int $pointId ,$number ,$beforePoint ,$afterPoint)
    {
        $this->logPoint(new PointUsage() ,$pointId ,$number ,$beforePoint ,$afterPoint);

        return PointUsage::where('user_id' ,$userId)
                         ->where('point_id' ,$pointId)
                         ->update([
                            'number' => $number
                         ]);
    }
}
