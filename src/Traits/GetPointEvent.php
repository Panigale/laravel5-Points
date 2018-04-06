<?php
/**
 * Author: Panigale
 * Date: 2018/4/6
 * Time: 下午3:14
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\PointEvent;

trait GetPointEvent
{
    /**
     * get user point event
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPointEvent()
    {
        return PointEvent::with('activities')->where('user_id' ,$this->id)->get();
    }
}