<?php
/**
 * Author: Panigale
 * Date: 2018/4/3
 * Time: 下午12:08
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panigale\Point\Traits\HasPoints;

class PointEvent extends Model
{
    use SoftDeletes ,HasPoints;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_events'));
    }

    public function owner()
    {
        return $this->belongsTo(config('points.table_names.users') ,'user_id');
    }

    /**
     * 事件名稱關聯
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(PointEventType::class ,'point_event_type_id');
    }

    /**
     * 這個事件所產生的點數活動
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(PointActivity::class ,'point_event_id');
    }

    /**
     * 關聯的事件 model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pointable()
    {
        return $this->morphTo();
    }


    public function refund($because = null)
    {
        $activities = $this->activities;
        $addPoint = [];

        foreach ($activities as $activity){

            $number = $activity->number;
            $point = $activity->rule()->name;
            $addPoint[$point] = $number;
        }

        $owner = $this->owner;

        $owner->addPoints($this->pointable ,'points.refund' ,$because ,$addPoint);
    }
}