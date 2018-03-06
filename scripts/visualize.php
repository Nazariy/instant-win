#!/usr/bin/php
<?php
$loader = require __DIR__ . '/../vendor/autoload.php';

use cli\Colors;
use InstantWin\{
    Player,
    TimePeriod,
    Distribution\EvenOverTimeDistribution,
    Distribution\FixedOddsDistribution
};

define('WINS_PER_DAY', 10);
define('OFFSET_TODAY', strtotime('today midnight'));
define('OFFSET_TOMORROW', strtotime('tomorrow midnight'));

Colors::enable();

$screenCols = exec('tput cols');

for ($tries = 0; $tries < 20; $tries++) {

    $durationInSeconds = 20000;

    $eachDot = ceil($durationInSeconds / ($screenCols - 3));

    $dist = new FixedOddsDistribution();

    $dist->setOdds(0.002);

    $player = new Player();
    $player->setDistribution($dist);
    $player->setMaxWins(WINS_PER_DAY);


//    $dist = new EvenOverTimeDistribution();

    $timePeriod = new TimePeriod();
    $timePeriod->setStartTimestamp(1);
    $timePeriod->setEndTimestamp($durationInSeconds);

//    $player = new Player();
//    $player->setDistribution($dist);
//    $player->setCurWins(0);
//    $player->setMaxWins(WINS_PER_DAY);
//    $player->setTimePeriod($timePeriod);


    $wins = 0;
    $plays = 0;
    for ($curTime = 0; $curTime <= $durationInSeconds; $curTime++) {

        $timePeriod->setCurrentTimestamp($curTime);
        $player->setPlayCount($curTime);

        $win = $player->isWinner();

        if ($win) {
            $wins++;
            $player->setCurWins($wins);
            printWin($wins);
        } else {
            if (($curTime % $eachDot) === 0) {
                printLossDot();
            }
        }

    }

    echo PHP_EOL . PHP_EOL;

}

function printWin($winCount)
{
    cli\out('%k%' . $winCount . $winCount);
}

function printLossDot()
{
    cli\out('%n%0.');
}