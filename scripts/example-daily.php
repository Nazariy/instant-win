#!/usr/bin/php
<?php
$loader = require __DIR__ . '/../vendor/autoload.php';

use InstantWin\Player;
use InstantWin\Distribution\EvenOverTimeDistribution;
use InstantWin\TimePeriod;

define('WINS_PER_DAY', 10);
define('OFFSET_TODAY', strtotime('today midnight'));
define('OFFSET_TOMORROW', strtotime('tomorrow midnight'));

$storage = new \InstantWin\StorageContainer([
    'players' => WINS_PER_DAY * 10,
    'winners' => 0,
    'numbers' => []
], true);

/**
 * Setup the distribution, time period and player
 */
$player = new Player();

$player->setMaxWins(WINS_PER_DAY);
$player->setCurWins($storage->offsetGet('winners'));
$player->setPlayCount($storage->offsetGet('players'));

$timePeriod = new TimePeriod();
$timePeriod->setStartTimestamp(OFFSET_TODAY);
$timePeriod->setEndTimestamp(OFFSET_TOMORROW);
$timePeriod->setCurrentTimestamp(time());
$player->setTimePeriod($timePeriod);

$player->setDistribution(new EvenOverTimeDistribution());

/**
 * Execute a single instant-win play attempt
 */
$win = $player->isWinner();

if ($win) {
    echo "You Won!!!\n";
    $storage->offsetIncrease('winners');
    $storage['numbers'][] = $storage['players'];
} else {
    echo "Sorry, you did not win.\n";
}
$storage->offsetIncrease('players');
$storage->saveCache();
var_dump($storage);
