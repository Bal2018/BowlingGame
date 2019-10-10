<?php

namespace App;

include_once "./App/BowlingPins.php";
include_once "./App/Game.php";
include_once "./App/Frame.php";
include_once "./App/FrameFactory.php";

do {
    $numFrames = (int)readline("How many frames in the game ");
    if ($numFrames <1) {
        exit("ERROR : Game OVER!!".PHP_EOL);
    }
    $factoryFrames = new FrameFactory();
    $listOfFrames= $factoryFrames->createList($numFrames);
    $game = new Game($listOfFrames, $factoryFrames);
    $game->play();

    echo("Total score for game = " . $totalGameScore = $game->getTotalScore()) . PHP_EOL;
    $continue = strtoupper(readline("Another game ? (Y/N)"));
} while ($continue == 'Y');
