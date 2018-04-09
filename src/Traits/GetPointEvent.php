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
    public function getPointEvent($name = null)
    {

        $query = PointEvent::with('activities')->where('user_id' ,$this->id);

        if(! $name){
            $pointType = PointEventType::where('name' ,$name)->first();

            $query->where('point_event_type_id' ,$pointType->id);
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
        $eventType = PointEventType::select('id')
                                ->where('is_increase' ,$isIncrease)
                                ->get();

        $query = PointEvent::with('activities')
                            ->whereIn('point_event_type_id' ,$eventType->toArray())
                            ->get();

        return $query;
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