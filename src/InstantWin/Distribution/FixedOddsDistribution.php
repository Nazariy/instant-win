<?php

namespace InstantWin\Distribution;

use OutOfRangeException;
use RangeException;
use UnexpectedValueException;

/**
 * Defines distribution logic for awarding wins by using fixed odds. This will not attempt
 * to spread the wins evenly over a time period.
 *
 * @author Konr Ness <konrness@gmail.com>
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
class FixedOddsDistribution extends AbstractDistribution
{
    /**
     * @var float
     */
    protected $_odds;

    /**
     * Get the odds for a single play at this moment in time
     *
     * @return float Number from 0.0001 to 0.9999
     * @throws \RangeException if odds are not set
     */
    public function getOdds(): float
    {
        if ($this->_odds === null) {
            throw new RangeException('Odds not set');
        }

        return $this->_odds;
    }

    /**
     * Sets the fixed odds for all plays
     *
     * @param float|double $odds
     * @return $this
     * @throws OutOfRangeException
     * @throws UnexpectedValueException
     */
    public function setOdds(float $odds): self
    {
        if (!is_numeric($odds)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Odds expected to be float. %s provided.',
                    \gettype($odds)
                )
            );
        }

        if ($odds > 1 || $odds < 0) {
            throw new OutOfRangeException(
                sprintf(
                    'Odds expected to be between %f and %f',
                    self::MIN_ODDS,
                    self::MAX_ODDS
                )
            );
        }

        $this->_odds = $odds;

        return $this;
    }
}
