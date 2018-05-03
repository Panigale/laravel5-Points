<?php
/**
 * Author: Panigale
 * Date: 2018/4/6
 * Time: 下午3:14
 */

namespace Panigale\Point\Traits;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Panigale\Point\Models\PointEvent;
use Panigale\Point\Models\PointEventType;

trait GetPointEvent
{
    /**
     * @var Model
     */
    private $queryBuilder;

    private function queryBuilder()
    {
        $this->queryBuilder = PointEvent::with(['type' ,'activities'])->where('user_id' ,$this->id);

        return $this;
    }

    /**
     * get user point event
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEvent($typeId = null)
    {
        $query = $this->queryBuilder();

        if(! is_null($typeId)){
            $query->where('point_event_type_id' ,$typeId);
        }

        return $this->queryBuilder->get();
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

    private function setBetweenDate(array $between)
    {
        $this->queryBuilder->whereDate('created_at' ,$between);

        return $this;
    }

    /**
     * 取出最近 30 天的記錄內容
     *
     * @param null $eventTypeId
     * @return mixed
     */
    public function getLast30DayEvent($eventTypeId = null)
    {
        $this->getByDays(30);

        if(!is_null($eventTypeId))
            $this->queryBuilder->where('point_event_type_id' ,$eventTypeId);

        return $this->queryBuilder->get();
    }

    /**
     * 取出最近 7 天的記錄內容
     *
     * @param null $eventTypeId
     * @return mixed
     */
    public function getLast7DayEvent($eventTypeId = null)
    {
        $this->getByDays(7);

        if(!is_null($eventTypeId))
            $this->queryBuilder->where('point_event_type_id' ,$eventTypeId);

        return $this->queryBuilder->get();
    }

    public function getByDays(int $days)
    {
        $this->queryBuilder = $this->queryBuilder();
        $dateStarted = Carbon::today();
        $dateEnded = $dateStarted->copy()->subDays($days);
        $this->setBetweenDate([$dateStarted ,$dateEnded]);

        return $this;
    }
}