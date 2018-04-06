<?php
/**
 * Author: Panigale
 * Date: 2018/4/5
 * Time: 下午10:52
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class PointEventType extends Model
{
    protected $table = 'point_event_type';

    protected $guarded = ['id'];

    /**
     * 擁有的事件
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(PointEvent::class ,'point_event_id');
    }
}