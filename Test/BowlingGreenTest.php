<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/** @test  */
class BowlingGameTest extends TestCase
{

    /**
     * @dataProvider max_Pins_Provider
     */
    public function test_Throw_Is_Within_Range($maxPins)
    {
        $throw = new \App\BowlingPins;
        $result = $throw->ballThrow($maxPins);

        $this->assertTrue($result >= 0 && $result <= $maxPins);
    }

    public function max_Pins_Provider()
    {
        return [
            [10],[9],[8],[7],[6],[5],[4],[3],[2],[1],[0]
        ];
    }

    public function test_2_Throw_In_Range_Of_10()
    {
        $frame = new \App\Frame(new \App\BowlingPins());
        $score1 = $frame->firstThrow();
        $score2 = $frame->secondThrow();

        $this->assertTrue($score2 >= 0 && ($score2<= 10 - $score1));
        return $this;
    }

    public function test_Strike_Detected()
    {
        $mockPins=$this->createMock(\APP\BowlingPins::class);

        $mockPins->expects($this->once())
            ->method('ballThrow')
            ->with($this->equalTo(10))
            ->willReturn(10);          //this is what we need it to return for it to be a strike

        $frame = new \App\Frame($mockPins);
        $frame->firstThrow();

        $this->assertTrue($frame->isStrike());
    }

    /**
     * @dataProvider spare_Map_Provider
     */
    public function test_Spare_Detected($pinMap)
    {
        $mockPins = $this->createMock(\APP\BowlingPins::class);

        $mockPins->expects($this->exactly(2))
            ->method('ballThrow')
            ->will($this->returnValueMap($pinMap));

        $frame = new \App\Frame($mockPins);

        $frame->firstThrow();
        $frame->secondThrow();
        $this->assertTrue($frame->isSpare());
    }

    public function test_Spare_Only_Happens_When_First_Throw_Is_Not_10()
    {
        $mockPins = $this->createMock(\APP\BowlingPins::class);

        $mockPins->expects($this->exactly(2))
            ->method('ballThrow')
            ->will($this->returnValueMap([[10, 10], [0, 0]]));

        $frame = new \App\Frame($mockPins);
        $frame->firstThrow();
        $frame->secondThrow();

        $this->assertFalse($frame->isSpare());
    }

    public function spare_Map_Provider()
    {
        return [
            "forsix" => [[[10, 4], [6, 6]]],
            "threeseven" => [[[10, 3], [7, 7]]],
            "fivefive" => [[[10, 5], [5, 5]]],
            "eighttwo" => [[[10, 8], [2, 2]]]
        ];
    }

    /**
     * @dataProvider  Pins_Map_Provider
     */
    public function test_Save_Frame_Result($pinsMap, $total)
    {
        $mockPins = $this->createMock(\APP\BowlingPins::class);

        $mockPins->expects($this->exactly(2))  //changed to 1 as only calling array once
            ->method('ballThrow')
            ->will($this->returnValueMap($pinsMap));

        $frame = new \App\Frame($mockPins);

        $frame->firstThrow();
        $frame->secondThrow();

        $result= $frame->getFrameScore();
        $this->assertEquals($total, $result);
    }

    public function Pins_Map_Provider()
    {
        return [
            "total_six" => [[[10, 4], [6, 2]], 6],
            "total_4" => [[[10, 2], [8, 2]], 4],
            "total_7" => [[[10, 5], [5, 2]],7],
            "total_3" => [[[10, 1], [9, 2]],3]
        ];
    }

}
