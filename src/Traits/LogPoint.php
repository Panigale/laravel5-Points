<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午1:14
 */

namespace Panigale\Point\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Panigale\Point\Models\PointIncrease;
use Panigale\Point\Models\PointEvent;

trait LogPoint
{
    /**
     * @var PointEvent
     */
    protected $event;

    protected $increaseBody;

    public function logPoint(Model $model, int $pointId, $point, $beforePoint, $afterPoint)
    {
        $log = $model::create([
            'user_id'      => $this->id,
            'before_number' => $beforePoint,
            'point_id'     => $pointId,
            'number'       => $point,
            'after_number' => $afterPoint
        ]);

        $this->event->save($log);
    }

    /**
     * @param PointIncrease $increase
     * @return $this
     */
    protected function setEvent(PointEvent $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    protected function setBody(string $body)
    {
        $this->increaseBody = $body;

        return $this;
    }
}