<?php
/**
 * Author: Panigale
 * Date: 2018/1/25
 * Time: 下午12:34
 */

namespace Panigale\Point\Models;


use Illuminate\Database\Eloquent\Model;
use Panigale\Point\Exceptions\PointRuleAlreadyExists;
use Panigale\Point\Exceptions\PointRuleNotExist;
use Panigale\Point\PointRuleRegistrar;

class PointRules extends Model
{
    public $guarded = ['id'];

    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('points.table_names.point_rules'));
    }

    /**
     * 建立點數規則
     *
     * @param array $attributes
     * @return mixed
     */
    public static function create(array $attributes = [])
    {
        if(PointRules::where('name', $attributes['name'])->first()){
//        if (static::getRules()->where('name', $attributes['name'])->first()) {
            throw PointRuleAlreadyExists::create($attributes['name']);
        }

        return parent::create($attributes);
    }

    /**
     * 取出全部的點數規則
     *
     * @return mixed
     */
    protected static function getRules()
    {
        return app(PointRuleRegistrar::class)->getRules();
    }

    /**
     * 依照點數名稱搜尋規則
     *
     * @param string $name
     * @return mixed
     */
    public static function findByName(string $name)
    {
        $rules = static::getRules()->where('name' ,$name)->first();

        if(!$rules){
            throw PointRuleNotExist::create($name);
        }

        return $rules;
    }
}