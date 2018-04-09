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
    public function createEvent(Model $model,$event ,$body ,$isAdd = true)
    {
        $eventType = $this->getEventType($event ,$isAdd);

        $this->event = $model->pointEvent->create([
            'point_event_type_id' => $eventType->id,
            'body' => $body,
            'user_id' => $this->id,
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
    protected function getEventType($event ,$isAdd)
    {
        $eventQuery = PointEventType::where('name' ,$event);

        if($isAdd)
            $eventQuery->where('is_increase' ,true);
        else
            $eventQuery->where('is_deduction' ,true);

        $event = $eventQuery->first();

        if(is_null($event))
            throw PointEventNotExist::create($event);

        return $event;
    }
}