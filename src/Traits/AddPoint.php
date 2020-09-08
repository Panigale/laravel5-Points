<?php
/**
 * Author: Panigale
 * Date: 2018/1/29
 * Time: 下午3:25
 */

namespace Panigale\Point\Traits;


use Panigale\Point\Models\Point;
use Panigale\Point\Models\PointActivity;

trait AddPoint
{
    /**
     * 點數 Model
     *
     * @var Point
     */
    protected $point;

    /**
     * rule id
     *
     * @var int
     */
    protected $ruleId;

    /**
     * @var string
     */
    protected $increaseTitle;

    /**
     * @var string
     */
    protected $increaseBody;

    /**
     * update user point
     *
     * @param int $ruleId
     * @param $number
     * @param $beforePoint
     * @param $afterPoint
     * @return mixed
     */
    protected function addPointToUser($number, $beforePoint, $afterPoint)
    {
        if($this->pointNotIsset())
            $point = $this->createPointToUser();
       else
            $point = Point::where('user_id' ,$this->id)
                         ->where('rule_id' ,$this->ruleId)
                        ->lockForUpdate()
                        ->first();

        $this->logPoint($point->id, $number, $beforePoint, $afterPoint);
        $point->number = $afterPoint;

        return $point->save();
    }

    /**
     * create point to user
     *
     * @param null $number
     */
    private function createPointToUser($number = null)
    {
        return Point::create([
            'user_id' => $this->id,
            'rule_id' => $this->ruleId,
            'number' => $number
        ]);
    }

    /**
     * 檢查使用者是否擁有這個點數
     *
     * @return mixed
     */
    protected function pointNotIsset()
    {
        $point = Point::where('user_id' ,$this->id)
                    ->where('rule_id' , $this->ruleId)
                    ->first();


        if(is_null($point))
            return true;

        $this->setPoint($point);

        return false;
    }

    /**
     * 設定目前在執行的 Point Model
     *
     * @param Point $point
     * @return $this
     */
    protected function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }
}