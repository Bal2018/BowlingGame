<?php

namespace App;

class BowlingPins
{
    public function ballThrow($maxPins)
    {
        return rand(0, $maxPins);
    }
}
