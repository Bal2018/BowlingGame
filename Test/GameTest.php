<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @dataProvider data_Provider_Games_Without_Strikes_Or_Spares
     */
    public function test_Total_Score_Is_Correct_Without_Strikes_Or_Spares($framesMap, $total)
    {
        $frames = [];

        foreach ($framesMap as $map) {
            $mockPins = $this->createMock(\APP\BowlingPins::class);
            $mockPins->expects($this->exactly(2))
                ->method('ballThrow')
                ->will($this->returnValueMap($map));

            array_push($frames, new \App\Frame($mockPins));
        }

        $game = new \App\Game($frames, new \App\FrameFactory());

        $game->play();
        $totalGameScore = $game->getTotalScore();

        $this->assertTrue($game->getTotalScore() == $total);
    }

    public function data_Provider_Games_Without_Strikes_Or_Spares()
    {
        return [
            "two_frames_7_total" => [
                [
                    [[10, 4], [6, 2]],
                    [[10, 1], [9, 0]]
                ],
                7
            ]
        ];
    }
    /**
     * @dataProvider data_Provider_Games_With_Spares
     */
    public function test_Total_Score_Is_Correct_With_Spares($framesMap, $frametotal)
    {
        $frames = [];

        foreach ($framesMap as $map) {
            $mockPins = $this->createMock(\APP\BowlingPins::class);
            $mockPins->expects($this->exactly(2))
                ->method('ballThrow')
                ->will($this->returnValueMap($map));

            array_push($frames, new \App\Frame($mockPins));
        }

        $game = new \App\Game($frames, new \App\FrameFactory());

        $game->play();
        $totalGameScore = $game->getTotalScore();
        echo "GAME TOTAL=" . $totalGameScore . PHP_EOL;

        $this->assertTrue($game->getTotalScore() == $frametotal);
    }



    public function data_Provider_Games_With_Spares()
    {
        return [
            "four_frames_18_total" => [
                [
                    [[10, 1], [9, 2]],
                    [[10, 0], [10, 0]],
                    [[10, 2], [8, 8]],
                    [[10, 2], [8, 1]]
                ],
                18
            ],
            "four_frames_21_total" => [
                [
                    [[10, 1], [9, 2]],
                    [[10, 9], [1, 1]],
                    [[10, 2], [8, 1]],
                    [[10, 2], [8, 1]]
                ],
                21
            ],
            "3frames_total_30SPARE" => [
                [
                    [[10, 2], [8, 3]],
                    [[10, 8], [2, 2]],
                    [[10, 6], [4, 3]]
                ],
                30
            ],
            "3frames_total_33STRIKE"=> [
                [
                    [[10, 2], [8, 3]],
                    [[10, 10], [0, 0]],
                    [[10, 6], [4, 3]]

                ],
                33
            ],
              "Kata10_frames_101_total" => [
                [
                    [[10, 1], [9, 5]],
                    [[10, 4], [6, 5]],
                    [[10, 6], [4, 4]],
                    [[10, 5], [5, 5]],
                    [[10, 10], [0, 0]],
                    [[10, 0], [10, 1]],
                    [[10, 7], [3, 3]],
                    [[10, 6], [4, 4]],
                    [[10, 10], [0, 0]],
                    [[10, 2], [8, 0]]
                ],
                110
            ]
        ];
    }

    /**
     * @dataProvider data_Provider_Games_With_Strikes
     */
    public function test_Total_Score_Is_Correct_With_Strike($framesMap, $frametotal)
    {
        $frames = [];

        foreach ($framesMap as $map) {
            $mockPins = $this->createMock(\APP\BowlingPins::class);
            $mockPins->expects($this->exactly(2))
                ->method('ballThrow')
                ->will($this->returnValueMap($map));

            array_push($frames, new \App\Frame($mockPins));
        }

        $game = new \App\Game($frames, new \App\FrameFactory());

        $game->play();

        $this->assertTrue($game->getTotalScore() == $frametotal);
    }


    public function data_Provider_Games_With_Strikes()
    {
        return [
           "3frames_total_33STRIKE"=> [
                [
                    [[10, 2], [8, 3]],
                    [[10, 10], [0, 0]],
                    [[10, 6], [4, 3]]

                ],
                33
            ],
            "Kata10Strike_frames_101_total" => [
                [
                    [[10, 1], [9, 6]],
                    [[10, 4], [6, 5]],
                    [[10, 6], [4, 4]],
                    [[10, 10], [0, 0]],
                    [[10, 4], [6, 2]],
                    [[10, 10], [0, 0]],
                    [[10, 5], [5, 4]]
                ],
                86
            ]
        ];
    }

    public function test_Allow_3Balls_If_Rolled_Strike_Or_Spare()
    {
        //total 1
        $mockPins = $this->createMock(\APP\BowlingPins::class);
        $mockPins->expects($this->exactly(2))
            ->method('ballThrow')
            ->will($this->returnValueMap([[10, 1], [9, 0]]));

        //total strike
        $mockPins2 = $this->createMock(\APP\BowlingPins::class);
        $mockPins2->expects($this->exactly(2))
            ->method('ballThrow')
            ->will($this->returnValueMap([[10, 10], [0, 0]]));

        //total 3
        $mockPins3 = $this->createMock(\APP\BowlingPins::class);
        $mockPins3->expects($this->exactly(1))
            ->method('ballThrow')
            ->will($this->returnValueMap([[10, 3]]));

        $mockFrameFactory = $this->createMock(\APP\FrameFactory::class);
        $mockFrameFactory->expects($this->exactly(1))
            ->method('create')
            ->will($this->returnValue(new \App\Frame($mockPins3)));


        $frames = [new \App\Frame($mockPins), new \App\Frame($mockPins2)];
        $game = new \App\Game($frames, $mockFrameFactory);

        $gameScore=$game->play();
        $this->assertTrue($game->getTotalScore() == 14);

    }
}
