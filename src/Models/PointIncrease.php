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

    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('points.table_names.point_increases'));
    }
}