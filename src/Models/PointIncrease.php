<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:35
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class PointIncrease extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_increases'));
    }

    /**
     * 取得這個點數增加的事件內容
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function event()
    {
        return $this->morphToMany(PointEvent::class ,'eventables');
    }
}