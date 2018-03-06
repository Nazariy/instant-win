<?php

namespace InstantWin;

use RuntimeException;

/**
 * Class TimePeriod
 * @package InstantWin
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
class TimePeriod
{

    /**
     * Timestamp of the beginning of the current time period
     *
     * @var int
     */
    protected $startTimestamp;

    /**
     * Timestamp of the end of the current time period
     *
     * @var int
     */
    protected $endTimestamp;

    /**
     * Allows for forcing the current timestamp for testing
     *
     * @var int|null
     */
    protected $currentTimestamp;

    /**
     * @return float
     * @throws \Exception
     */
    public function getCompletion(): float
    {
        // force completion to be greater than 0
        return max(1, $this->getCurrentTimestamp() - $this->getStartTimestamp()) / $this->getDuration();
    }

    /**
     * @param int $endTimestamp
     * @return $this;
     */
    public function setEndTimestamp(int $endTimestamp): self
    {
        $this->endTimestamp = $endTimestamp;
        return $this;
    }

    /**
     * @throws RuntimeException
     * @return int
     */
    public function getEndTimestamp(): int
    {
        if (!$this->endTimestamp) {
            throw new RuntimeException('EndTimestamp not set');
        }
        return $this->endTimestamp;
    }

    /**
     * @param int $startTimestamp
     * @return $this;
     */
    public function setStartTimestamp($startTimestamp): self
    {
        $this->startTimestamp = $startTimestamp;
        return $this;
    }

    /**
     * @throws \Exception
     * @return int
     */
    public function getStartTimestamp(): int
    {
        if (!$this->startTimestamp) {
            throw new RuntimeException('StartTimestamp not set');
        }
        return $this->startTimestamp;
    }

    /**
     * @param int $currentTimestamp
     * @return $this;
     */
    public function setCurrentTimestamp($currentTimestamp): self
    {
        $this->currentTimestamp = $currentTimestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentTimestamp(): int
    {
        if (null === $this->currentTimestamp) {
            return time();
        }

        return $this->currentTimestamp;
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function getDuration(): int
    {
        return $this->getEndTimestamp() - $this->getStartTimestamp();
    }
}
