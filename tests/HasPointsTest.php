<?php

use Panigale\Tests\TestCase;

/**
 * Author: Panigale
 * Date: 2018/1/19
 * Time: 下午3:27
 */

class HasPointsTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_add_points(){
        //arrange
        $this->testUser->addPoints(['一般點數' => 200]);

        //action

        //assert
    }

    /**
     * @test
     */
    public function it_should_test_aaa(){
        //arrange
        $aaa = 't';

        //action

        //assert
        $this->assertEquals('t' ,$aaa);


    }
}