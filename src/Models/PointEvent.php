<?php
/**
 * Author: Panigale
 * Date: 2018/4/3
 * Time: 下午12:08
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class PointEvent extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.point_events'));
    }

    /**
     * 取得所有擁有點數事件的模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function increases()
    {
        return $this->morphedByMany(PointIncrease::class ,'point_event_able');
    }

    /**
     * 取得所有擁有點數事件的模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function usages()
    {
        return $this->morphedByMany(PointUsage::class ,'point_event_able');
    }
}