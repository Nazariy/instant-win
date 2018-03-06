<?php

namespace InstantWin\Distribution;

/**
 * Interface WinAmountAwareInterface
 * @package InstantWin\Distribution
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
interface WinAmountAwareInterface
{
    /**
     * @param int $currentWinCount
     * @return AbstractDistribution
     */
    public function setCurrentWinCount(int $currentWinCount): AbstractDistribution;

    /**
     * @param int $playCount
     * @return AbstractDistribution
     */
    public function setPlayCount(int $playCount): AbstractDistribution;

    /**
     * @param int $maxWinCount
     * @return AbstractDistribution
     */
    public function setMaxWinCount(int $maxWinCount): AbstractDistribution;
}
