<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:34
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $guarded = ['id'];

    /**
     * Point constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('points.table_names.points'));
    }

    /**
     * point rules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rule()
    {
        return $this->belongsTo(PointRules::class ,'rule_id');
    }
}