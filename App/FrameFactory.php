<?php

namespace App;

class FrameFactory
{
    public function create()
    {
        return new Frame(new BowlingPins());
    }

    public function createList($n)
    {

        $frames = [];
        foreach (range(1, $n) as $i) {
            array_push($frames, $this->create());
        }
        return $frames;
    }
}
