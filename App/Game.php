<?php

namespace App;

class Game
{
    private $frames = [];

    private $frameFactory;

    /**
     * @param Frame[] $frames
     * @param FrameFactory $frameFactory
     */
    public function __construct(array $frames, $frameFactory)
    {
        $this->frames = $frames;
        $this->frameFactory = $frameFactory;
    }

    public function play()
    {
        $maxFrames = count($this->frames);
        var_dump($maxFrames);
        for ($frameIndex = 0; $frameIndex < count($this->frames); $frameIndex++) {
            //      for ($frameIndex = 0; $frameIndex < 2; $frameIndex++) {

            $frame = $this->frames[$frameIndex];

            $firstThrowScore = $frame->firstThrow();
            $secondThrowScore = $frame->secondThrow();

            //         echo "FRAME Score : " . $frame->getFrameScore() . PHP_EOL;

            /** @var Frame $previousFrame */
            $previousFrame = $this->frames[$frameIndex - 1] ?? null;
            if ($previousFrame && $previousFrame->isSpare()) {
                $previousFrame->addExtraScore($firstThrowScore);
            }

            if ($previousFrame && $previousFrame->isStrike()) {
                $tempDisplayTotal = ($firstThrowScore+$secondThrowScore);
                $previousFrame->addExtraScore($tempDisplayTotal);
            }
        }

        $lastFrame = $this->frames[$maxFrames-1];

        if (($lastFrame->isStrike() || $lastFrame->isSpare())) {
            $extraFrame = $this->frameFactory->create();
            $extraFrame->firstThrow();
            array_push($this->frames, $extraFrame);
        }

        return $this->getTotalScore();
    }

    public function getTotalScore()
    {
        $totalScore = 0;

        for ($frameIndex = 0; $frameIndex < count($this->frames); $frameIndex++) {
            $framescore = $this->frames[$frameIndex]->getFrameScore();
            $totalScore += $framescore;
            //echo ("INDEX= $frameIndex>>Framescore=   $framescore  Totalsofar= $totalScore").PHP_EOL;
        }

        return $totalScore;
    }
}
