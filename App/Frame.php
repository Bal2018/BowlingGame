<?php


namespace App;

class Frame
{
    /**
     * @var \App\BowlingPins
     */
    private $pins;

    private $numOfThrows = 0;

    private $firstThrowScore = 0;

    private $secondThrowScore = 0;

    private $frameScore = 0;

    public function __construct(BowlingPins $pins)
    {
        $this->pins = $pins;
    }

    public function firstThrow()
    {
        if ($this->numOfThrows > 0) {
            throw new \Exception("ERROR: Cannot perform first throw more than once");
        }

        $this->firstThrowScore = $this->pins-> ballThrow(10);
        $this->frameScore += $this->firstThrowScore;
        $this->numOfThrows++;

        return $this->firstThrowScore;
    }

    public function secondThrow()
    {
        if ($this->numOfThrows !== 1) {
            throw new \Exception("ERROR: Invalid second throw call");
        }

        $this->secondThrowScore = $this->pins->ballThrow(10 - $this->firstThrowScore);
        $this->frameScore += $this->secondThrowScore;
        $this->numOfThrows++;

        return $this->secondThrowScore;
    }

    public function isStrike()
    {
        return $this->firstThrowScore == 10;
    }

    public function isSpare()
    {
        return (($this->firstThrowScore !== 10) &&
                    ($this->firstThrowScore + $this->secondThrowScore == 10));
    }

    public function getFrameScore()
    {
        return $this->frameScore;
    }

    public function addExtraScore(int $extra)
    {
        $this->frameScore += $extra;
    }
}
