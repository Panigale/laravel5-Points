# Laravel5 Point System
## Install
```
composer require panigale/laravel5-point-system

php artisan vendor:publish --provider="Panigale\PointSystemServiceProvider"
```

migrate point tables
```
php artisan migrate
```


## 功能
```
use Illuminate\Foundation\Auth\User as Authenticatable;
use Panigale\Point\Traits\HasRoles;

class User extends Authenticatable
{
    use HasPoints;

    // ...
}
```

可以自定義點數的名目，例如：

建立一個點數項目名為 event:1，並且這個點數將在 $expiryDateTime 過期

## Create Point Rule

```
PointRoles::create(‘event:1’ ,$expiryDateTime)
```

## add Points
並將點數賦予給 user

```
$user->addPoints('event:1' ,$numbers)
```


## usage Point
將點數從 user 身上扣除
```
$user->usagePoint('event:1' ,$numbers)

or 

$user->usagePoint([
	'point1' => 200,
	'point2' => 300
])
```
會取出所有未過期的點數，如果沒有指定什麼種類扣除，將會自動依照建立順序進行扣除（先進先出）

## current Points

取得使用者目前所有可用點數
```
$currentPoints = $user-> currentPoints()
$event1Point = $currentPoints->even_1
```

## all Points
取得使用者目前的所有點數（包含不可用）
```
$points = $user->allPoints()
```

## enoughToUsePoint
點數是否足夠使用
```
$user->enoughToUsePoint([
	'一般點數' => 200,
	'紅利點數' => 500
])

or

$user->enoughToUsePoint(200)
```



## migration
1. point_rules
2. points
3. point_usages

## License

Skeleton is released under the [MIT License](LICENSE).