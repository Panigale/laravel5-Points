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
    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('points.table_names.points'));
    }

    /**
     * point rules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rules()
    {
        return $this->belongsTo(PointRules::class);
    }
}