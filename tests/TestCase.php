<?php

namespace Panigale\Point\Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var User
     */
    protected $testUser;

    protected function setUp()
    {
        parent::setUp();

//        $this->testUser = User::find(1);
    }
}
