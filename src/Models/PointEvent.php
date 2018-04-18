<?php
/**
 * Author: Panigale
 * Date: 2018/4/3
 * Time: 下午12:08
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointEvent extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_events'));
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
}