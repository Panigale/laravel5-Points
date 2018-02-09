<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:25
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\Point;
use Panigale\Point\Models\PointIncrease;

trait AddPoint
{
    protected function addPointToUser(int $ruleId, $number, $beforePoint, $afterPoint)
    {
        $this->logPoint(new PointIncrease(), $ruleId, $number, $beforePoint, $afterPoint);

        return Point::where('user_id' ,$this->id)
                    ->where('rule_id' ,$ruleId)
                    ->update([
                        'number' => $afterPoint
                    ]);
    }
}