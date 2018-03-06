<?php

namespace InstantWin\Distribution;

/**
 * Class AbstractDistribution
 * @package InstantWin\Distribution
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
abstract class AbstractDistribution
{
    const MIN_ODDS = 0.00001;
    const MAX_ODDS = 0.99999;

    /**
     * Get the odds for a single play at this moment in time
     *
     * @return float Number from 0.00001 to 0.99999
     */
    abstract public function getOdds(): float;
}
