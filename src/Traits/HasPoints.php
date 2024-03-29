<?php
/**
 * Author: Panigale
 * Date: 2018/1/19
 * Time: 下午3:00
 */

namespace Panigale\Point\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Panigale\Point\Exceptions\PointNotEnough;
use Panigale\Point\Exceptions\PointRuleNotExist;
use Panigale\Point\Models\Point;
use Panigale\Point\Models\PointRules;

trait HasPoints
{
    use LogPoint, GetPointEvent;

    private $chargeWithout = [];

    /**
     * 增加點數
     *
     * @param array $points
     * @return $this
     */
    public function addPoints(Model $model ,string $because ,$body ,array $points)
    {
        DB::transaction(function () use ($model ,$because ,$body ,$points){
            $pointCollection = collect($points);
            $total = $pointCollection->sum();
            $this->createEvent($model ,$because ,$body ,$total);

            $pointCollection->map(function ($number, $name) {
                $pointRule = PointRules::findByName($name);
                $pointRuleId = $pointRule->id;
                $this->ruleId = $pointRuleId;

                $currentPoint = $this->currentPoint($pointRuleId);
                $afterPoint = $currentPoint + $number;
                $this->addPointToUser($number, $currentPoint, $afterPoint);
            });
        });

        return $this;
    }

    /**
     * 取得有效點數的總和
     *
     * @return int|mixed
     */
    public function currentPoint($pointRuleId = null) : int
    {
        if (is_null($pointRuleId)){
            return $this->getCanUsePoints()->sum('number');
        }

        if (is_string($pointRuleId)) {
            $point = PointRules::where('name', $pointRuleId)->first();

            if (is_null($point)) {
                throw PointRuleNotExist::create($pointRuleId);
            }

            $pointRuleId = $point->id;
        }

        $currentPoint = Point::where('user_id', $this->id)
                            ->where('rule_id', $pointRuleId)
                            ->first();

        if (is_null($currentPoint))
            return 0;

        return $currentPoint->number;
    }

    /**
     * 扣除點數，可以單純的使用 int，或是傳入 array 對每個點數進行扣點
     *
     * @param array ...$points
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|HasPoints[]|$this
     */
    public function usagePoint(Model $model ,$because ,$body ,...$points)
    {
        $currentPoint = $this->currentPoint();

        $userId = $this->id;
        /**
         * 如果點數是以 int 型態進來，檢查點數是否足夠
         *
         * 如果足夠，依照點數的建立順序開始扣點
         **/

        if (is_int($points[0])) {

            $points = $points[0];

            if ($points > $currentPoint) {
                throw PointNotEnough::create();
            } else {

                $this->createEvent($model ,$because ,$body ,$points ,false);

                foreach ($this->getCanUsePoints() as $point){

                    if ($points == 0)
                        break;

                    $numberOfPoint = $point->number;

                    if($numberOfPoint > 0) {

                        /**
                         * 如果要扣除的總點數大於這個點數項目，就扣除這個點數總額
                         *
                         * 如果要扣除的點數小於等於這個點數，就只扣除 points
                         */
                        if ($points > $numberOfPoint) {
                            $shouldDeductionPoint = $numberOfPoint;
                        } else {
                            $shouldDeductionPoint = $points;
                        }


                        $points -= $shouldDeductionPoint;

                        $this->usagePointToUser($point->id, $shouldDeductionPoint, $numberOfPoint, $numberOfPoint - $shouldDeductionPoint);
                    }
                };

                return $this->currentPoint();
            }
        }


        /**
         * 如果點數是以陣列型態進來
         */
        $points = array_shift($points);

        if ($this->notEnoughPointToUse($points)) {
            throw PointNotEnough::create();
        }

        $pointCollection = collect($points);

        $this->createEvent($model ,$because ,$body ,$pointCollection->sum() ,false);

        $userPoints = $this->getCanUsePoints();

        // map 需要扣點的項目
        $pointCollection->map(function ($point, $name) use ($userPoints, $userId) {

            //依序取出需要扣點項目的實際數量
            $shouldDeductionPoint = $userPoints->where('name', $name)->first();
            $number = $shouldDeductionPoint->number;

            $this->usagePointToUser($shouldDeductionPoint->id, $point, $number, $number - $point);
        });

        return $this;
    }

    /**
     * 取出所有能夠使用的點數項目
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCanUsePoints()
    {
        $userId = $this->id;

        $query = Point::select('points.*', 'point_rules.id as ruleId', 'point_rules.name as name', 'point_rules.expiry_at')
            ->join('point_rules', 'points.rule_id', '=', 'point_rules.id')
            ->where('points.user_id', $userId)
//                    ->where('point_rules.expiry_at', '>=', $nowDataTime)
//                    ->orWhere('point_rules.expiry_at', null)
            ->orderBy('point_rules.created_at', 'desc');

        if(count($this->chargeWithout) > 0){
            $query->whereNotIn('point_rules.name' ,$this->chargeWithout);
        }

        return $query->get();
    }

    public function chargeWithOut(...$without)
    {
        if(is_array($without[0]))
            $this->chargeWithout = $without[0];

        $this->chargeWithout = $without;

        return $this;
    }

    /**
     * 檢查點數是不是足夠用，如果傳入 int 的話只會檢查總點數
     *
     * 如果傳入陣列則會每項檢查是否足夠或是使用者不擁有這個點數
     *
     * @param array ...$points
     * @return bool
     */
    public function notEnoughPointToUse(...$points)
    {
        $currentPoint = $this->currentPoint();
        /**
         * 檢查是不是有足夠的點數可以用
         *
         * 如果傳入值是 int，只需要比對總數是否足夠
         *
         * 如果傳入陣列，則需每個比對是否足夠，如果不夠，回應差多少
         */
        if (is_int($points[0]))
            return $currentPoint < $points[0];

        $currentPoint = $this->getCanUsePoints();
        $points = array_shift($points);
        $notEnoughPoint = collect($points)->map(function ($point, $key) use ($currentPoint) {

            $pointType = $currentPoint->where('name', $key)->first();

            //如果使用者沒有擁有這個點數
            if (is_null($pointType))
                return false;
//                return 0 - $pointType;
            //如果使用者這這個點數項目不夠
            else if ($pointType->number < $point)
                return false;
            //返回扣除後剩下幾點
            else
                return $pointType->number - $point;
        })->values()->all();


        return in_array(false ,$notEnoughPoint ,true);
    }

    /**
     * 從陣列中取出點數總和
     *
     * @param array $points
     * @return mixed
     */
    protected function getCurrentPointFromArr(array $points)
    {
        return collect($points)->flatten()->sum();
    }
}