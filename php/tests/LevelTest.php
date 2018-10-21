<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DJB\{Level1, Level2, Level3, Level4, Level5};

final class LevelTest extends TestCase
{
    public function testLevel1()
    {
        $level = new Level1();
        $this->assertEquals($level->getExpectedOutput(),$level->run());
    }

    public function testLevel2()
    {
        $level = new Level2();
        $this->assertEquals($level->getExpectedOutput(),$level->run());
    }

    public function testLevel3()
    {
        $level = new Level3();
        $this->assertEquals($level->getExpectedOutput(),$level->run());
    }

    public function testLevel4()
    {
        $level = new Level4();
        $this->assertEquals($level->getExpectedOutput(),$level->run());
    }

    public function testLevel5()
    {
        $level = new Level5();
        $this->assertEquals($level->getExpectedOutput(),$level->run());
    }        
}