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
    private $eventQuery;

    private $paginateMode = null;

    private function buildQuery()
    {
        $this->paginateMode = request()->paginate;

        $this->eventQuery = PointEvent::with(['type' ,'activities'])->where('user_id' ,$this->id);
        $this->setDateBetween();

        return $this;
    }

    /**
     * get user point event
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEvent($typeId = null)
    {
        $this->buildQuery();
        $query = $this->eventQuery;

        if(! is_null($typeId)){
            $query->where('point_event_type_id' ,$typeId);
        }

        return $this->doQuery();
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

        $this->eventQuery = PointEvent::with('activities')
            ->whereIn('point_event_type_id' ,$eventType->toArray())
            ->orderBy('created_at' ,'desc');

        return $this->doQuery();
    }

    public function getPointEventByEventId($eventTypeId)
    {
        $this->eventQuery = PointEvent::where('point_event_id' ,$eventTypeId)
            ->orderBy('created_at' ,'desc');

        $this->setDateBetween();

        return $this->doQuery();
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
        $this->eventQuery->whereBetween('created_at' ,$between);

        return $this;
    }

    /**
     * 取出最近 7 天的記錄內容
     *
     * @param null $eventTypeId
     * @return mixed
     */
    public function getBeforeDay($days = 7,$eventTypeId = null)
    {
        $this->getByDays($days);

        if(!is_null($eventTypeId))
            $this->eventQuery->where('point_event_type_id' ,$eventTypeId);

        return $this->doQuery();
    }

    public function doQuery()
    {
        if(isset(request()->date))
            $this->eventQuery->whereDate('created_at' ,request()->date);

        return $this->paginateMode == true ? $this->eventQuery->paginate($this->perPage()) : $this->eventQuery->get();
    }

    protected function perPage()
    {
        $defaultPerPage = 30;
        $perPage = request()->perPage;

        return is_null($perPage) ? $defaultPerPage : $perPage;
    }

    protected function setDateBetween()
    {
        $startDate = request()->startDate;
        $endDate = request()->endDate;
        $date = request()->date;

        if(!is_null($date))
            $this->eventQuery->whereDate('created_at' ,$date);
        elseif (!is_null($startDate) && !is_null($endDate))
            $this->setBetweenDate([Carbon::createFromFormat('Y-m-d' ,$startDate)->startOfDay() ,Carbon::createFromFormat('Y-m-d' ,$endDate)->endOfDay()]);

        return $this;
    }

    public function getByDays(int $days)
    {
        $this->buildQuery();
        $dateEnded = Carbon::today()->endOfDay();
        $dateStarted = $dateEnded->copy()->subDays($days)->startOfDay();

        $this->setBetweenDate([$dateStarted ,$dateEnded]);

        return $this;
    }
}