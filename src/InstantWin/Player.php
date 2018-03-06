<?php

namespace InstantWin;

use InstantWin\Distribution\{
    AbstractDistribution,
    TimePeriodAwareInterface,
    WinAmountAwareInterface
};
use RangeException;

/**
 * Allows for executing a play on an instant-win game
 *
 * @author Konr Ness <konrness@gmail.com>
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
class Player
{
    /**
     * @var AbstractDistribution
     */
    protected $distribution;

    /**
     * @var TimePeriod
     */
    protected $timePeriod;

    /**
     * Maximum number of wins allowed in current time period
     *
     * @var int
     */
    protected $maxWins;

    /**
     * Current number of wins awarded in current time period
     *
     * @var int
     */
    protected $curWins = 0;

    /**
     * Current number of plays that have occurred in current time period
     *
     * @var int
     */
    protected $playCount = 0;

    /**
     * Execute one instant-win play and decide if player is a winner
     *
     * @return boolean true = win; false = lose
     * @throws \Exception
     */
    public function isWinner(): bool
    {
        if ($this->getCurWins() >= $this->getMaxWins()) {
            return false;
        }
        $distribution = $this->getDistribution();
        switch(true){
            case $distribution instanceof TimePeriodAwareInterface:
                /** @var TimePeriodAwareInterface $distribution */
                $distribution->setTimePeriod($this->getTimePeriod());
                break;
            case $distribution instanceof WinAmountAwareInterface:
                /** @var WinAmountAwareInterface $distribution */
                $distribution->setCurrentWinCount($this->getCurWins());
                $distribution->setMaxWinCount($this->getMaxWins());
                $distribution->setPlayCount($this->getPlayCount());
                break;
        }

        $odds = $distribution->getOdds();

        return $this->generateRandomFloat() <= $odds;
    }

    /**
     * @param AbstractDistribution $distribution
     * @return $this;
     */
    public function setDistribution(AbstractDistribution $distribution): self
    {
        $this->distribution = $distribution;

        return $this;
    }

    /**
     * @throws \Exception
     * @return AbstractDistribution
     */
    public function getDistribution(): AbstractDistribution
    {
        if (!$this->distribution) {
            throw new RangeException('Distribution not set');
        }
        return $this->distribution;
    }

    /**
     * @param \InstantWin\TimePeriod $timePeriod
     * @return $this;
     */
    public function setTimePeriod(TimePeriod $timePeriod): self
    {
        $this->timePeriod = $timePeriod;
        return $this;
    }

    /**
     * @throws \Exception
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
     * @param int $curWins
     * @return $this;
     */
    public function setCurWins(int $curWins): self
    {
        $this->curWins = $curWins;
        return $this;
    }

    /**
     * @throws \Exception
     * @return int
     */
    public function getCurWins(): int
    {
        if (null === $this->curWins) {
            throw new RangeException('CurWins not set');
        }
        return $this->curWins;
    }

    /**
     * @param int $maxWins
     * @return $this;
     */
    public function setMaxWins($maxWins): self
    {
        $this->maxWins = $maxWins;
        return $this;
    }

    /**
     * @throws \Exception
     * @return int
     */
    public function getMaxWins(): int
    {
        if (!$this->maxWins) {
            throw new RangeException('MaxWins not set');
        }
        return $this->maxWins;
    }

    /**
     * @param int $playCount
     * @return $this;
     */
    public function setPlayCount($playCount): self
    {
        $this->playCount = $playCount;
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
     * Roll the dice
     *
     * @return float
     */
    private function generateRandomFloat(): float
    {
        return mt_rand() / mt_getrandmax();
    }
}
