<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:57
 */

namespace Panigale\Point;


use Panigale\Point\Models\PointRules;

class PointRuleRegistrar
{
    public function getRules()
    {
        return app(PointRules::class)->get();
    }
}