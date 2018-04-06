<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:35
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class PointActivity extends Model
{
    protected $guarded = ['id'];

    /**
     * PointActivity constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_activities'));
    }

    /**
     * 這個點數活動的點數名稱
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function point()
    {
        return $this->belongsTo(Point::class ,'point_id');
    }

    /**
     * 取得這個點數增加的事件內容
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(PointEvent::class ,'point_event_id');
    }
}