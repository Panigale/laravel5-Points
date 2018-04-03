<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:34
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class PointUsage extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * PointUsage constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_usages'));
    }

    /**
     * 取得這個點數增加的事件內容
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function event()
    {
        return $this->morphMany(PointEvent::class ,'eventable');
    }
}