<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午1:14
 */

namespace Panigale\Point\Traits;


use Illuminate\Database\Eloquent\Model;
use Panigale\Point\Exceptions\PointEventNotExist;
use Panigale\Point\Models\PointActivity;
use Panigale\Point\Models\PointEvent;
use Panigale\Point\Models\PointEventType;

trait LogPoint
{
    use AddPoint,UsagePoint;

    /**
     * @var PointEvent
     */
    protected $event;


    /**
     * @param $event
     * @param int $pointId
     * @param $point
     * @param $beforePoint
     * @param $afterPoint
     * @return mixed
     */
    public function logPoint(int $pointId, $point, $beforePoint, $afterPoint)
    {
        return PointActivity::create([
            'before_point'   => $beforePoint,
            'point_event_id' => $this->event->id,
            'point_id'       => $pointId,
            'number'         => $point,
            'after_point'    => $afterPoint,
        ]);
    }

    /**
     * 建立事件
     *
     * @param Model $model
     * @param $event
     * @param $body
     * @param bool $isAdd
     * @return $this
     */
    public function createEvent(Model $model,$event ,$body ,$totalUse ,$isAdd = true)
    {
        $eventType = $this->getEventType($event ,$isAdd);
        $nowHave = $this->getCanUsePoints()->sum('number');
        $totalAfter = $isAdd ? $totalUse + $nowHave : $nowHave - $totalUse;

        $this->event = $model->pointEvent()->create([
            'point_event_type_id' => $eventType->id,
            'body' => $body,
            'user_id' => $this->id,
            'total' => $totalUse,
            'total_before' => $nowHave,
            'total_after' => $totalAfter
        ]);

        return $this;
    }

    /**
     * 取得點數事件的 id
     *
     * @param $event
     * @param $isAdd
     * @return mixed
     */
    protected function getEventType($eventName ,$isAdd)
    {
        $eventQuery = PointEventType::where('name' ,$eventName);

        if($isAdd)
            $eventQuery->where('is_increase' ,true);
        else
            $eventQuery->where('is_deduction' ,true);

        $event = $eventQuery->first();

        if(is_null($event))
            throw PointEventNotExist::create($eventName);

        return $event;
    }
}