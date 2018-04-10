<?php
/**
 * Author: Panigale
 * Date: 2018/4/6
 * Time: 下午3:14
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\PointEvent;
use Panigale\Point\Models\PointEventType;

trait GetPointEvent
{
    /**
     * get user point event
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEvent($typeId = null)
    {

        $query = PointEvent::with('activities')->where('user_id' ,$this->id);

        if(! is_null($typeId)){
            $query->where('point_event_type_id' ,$typeId);
        }
        
        return $query->get();
    }

    /**
     * get point event by type
     *
     * @param bool $isIncrease
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEventByType($isIncrease = true)
    {
        $query = PointEventType::select('id');

        $isIncrease ? $query->where('is_increase' ,$isIncrease) : $query->where('is_deduction');
        $eventType = $query->get();

        $query = PointEvent::with('activities')
                            ->whereIn('point_event_type_id' ,$eventType->toArray())
                            ->orderBy('created_at' ,'desc')
                            ->get();

        return $query;
    }

    public function getPointEventByEventId($eventTypeId)
    {
        return PointEvent::where('point_event_id' ,$eventTypeId)
                         ->orderBy('created_at' ,'desc')
                        ->get();
    }

    /**
     * get point event type
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEventType()
    {
        return PointEventType::all();
    }
}