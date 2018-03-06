<?php

namespace InstantWin\Distribution;

use InstantWin\TimePeriod;
use RangeException;

/**
 * Defines distribution logic for spreading wins evenly over a time period when
 * the number of total plays in the time period can not be known.
 *
 * @author Konr Ness <konrness@gmail.com>
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
class EvenOverTimeDistribution extends AbstractDistribution implements
    TimePeriodAwareInterface,
    WinAmountAwareInterface
{
    /**
     * @var TimePeriod
     */
    protected $timePeriod;

    /**
     * @var int
     */
    protected $currentWinCount;

    /**
     * @var int
     */
    protected $playCount;

    /**
     * @var int
     */
    protected $maxWinCount;

    /**
     * Get the odds for a single play at this moment in time
     *
     * @return float Number from 0.00001 to 0.99999
     * @throws \Exception
     */
    public function getOdds(): float
    {
        // determine percentage of wins awarded
        $timePercentage = $this->getTimePeriod()->getCompletion();

        $desiredWinCount = $timePercentage * $this->getMaxWinCount();
        $playCount = $this->getPlayCount();

        // this assumes a linear distribution of plays throughout the day
        $estimatedRemainingPlays = max(1, ($playCount / $timePercentage) - $playCount);

        return ($desiredWinCount - $this->getCurrentWinCount()) / $estimatedRemainingPlays * 5;

    }

    /**
     * @throws RangeException
     * @return \InstantWin\TimePeriod
     */
    public function getTimePeriod(): TimePeriod
    {
        if (!$this->timePeriod) {
            throw new RangeException('TimePeriod not set');
        }
        return $this->timePeriod;
    }

    /**
     * @param TimePeriod $timePeriod
     * @return $this
     */
    public function setTimePeriod(TimePeriod $timePeriod): AbstractDistribution
    {
        $this->timePeriod = $timePeriod;
        return $this;
    }

    /**
     * @throws \Exception
     * @return int
     */
    public function getMaxWinCount(): int
    {
        if (null === $this->maxWinCount) {
            throw new RangeException('MaxWinCount not set');
        }
        return $this->maxWinCount;
    }

    /**
     * @param int $maxWinCount
     * @return $this
     */
    public function setMaxWinCount(int $maxWinCount): AbstractDistribution
    {
        $this->maxWinCount = $maxWinCount;
        return $this;
    }

    /**
     * @throws \Exception
     * @return int
     */
    public function getPlayCount(): int
    {
        if (null === $this->playCount) {
            throw new RangeException('PlayCount not set');
        }
        return $this->playCount;
    }

    /**
     * @param int $playCount
     * @return $this;
     */
    public function setPlayCount(int $playCount): AbstractDistribution
    {
        $this->playCount = $playCount;
        return $this;
    }

    /**
     * @throws RangeException
     * @return int
     */
    public function getCurrentWinCount(): int
    {
        if (null === $this->currentWinCount) {
            throw new RangeException('CurrentWinCount not set');
        }
        return $this->currentWinCount;
    }

    /**
     * @param int $currentWinCount
     * @return $this
     */
    public function setCurrentWinCount(int $currentWinCount): AbstractDistribution
    {
        $this->currentWinCount = $currentWinCount;
        return $this;
    }
}
