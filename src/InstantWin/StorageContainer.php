<?php
/**
 * @project daily-draw
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 * @copyright leobit.co 2018
 */

namespace InstantWin;

use SplFileObject;

/**
 * Class StorageContainer
 * @package InstantWin
 * @author Nazariy Slyusarchuk <ns@leobit.co>
 */
class StorageContainer extends \ArrayObject
{
    const ACTIVITY_LOG = 'activity-%s.log';

    protected $useCache = false;

    public function __construct($input = [], $useCache = true)
    {
        $this->useCache = $useCache;

        if ($useCache) {
            try {
                $cache = $this->loadCache();

                if (!empty($cache)) {
                    $input = array_merge($input, $cache);
                }
            } catch (\Exception $e) {

            }
        }

        parent::__construct($input);
    }

    /**
     * loadCache
     * @return array
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function loadCache(): array
    {
        $file = $this->getFileStorage();

        if ($file->isReadable()) {
            $file->rewind();
            $string = $file->current();
            if (\strlen($string) > 0) {

                $instance = unserialize($string, [self::class]);
                if ($instance instanceof self) {
                    return $instance->getArrayCopy();
                }
            }
        }
        return [];
    }

    /**
     * getFileStorage
     * @return SplFileObject
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function getFileStorage(): SplFileObject
    {
        return new SplFileObject(
            getcwd() . DIRECTORY_SEPARATOR . sprintf(self::ACTIVITY_LOG, date('Y-m-d')),
            'c+'
        );
    }

    /**
     * offsetIncrease
     * @param string $index
     * @return $this
     */
    public function offsetIncrease(string $index): self
    {
        if ($this->offsetExists($index)) {
            $this[$index]++;
        } else {
            $this->offsetSet($index, 1);
        }
        return $this;
    }

    /**
     * offsetDecrease
     * @param string $index
     * @return $this
     */
    public function offsetDecrease(string $index): self
    {
        if ($this->offsetExists($index)) {
            $this[$index]--;
        } else {
            $this->offsetSet($index, 0);
        }
        return $this;
    }

    /**
     * offsetAppend
     * @param string $index
     * @param mixed $value
     * @return $this
     */
    public function offsetAppend(string $index, $value): self
    {
        if ($this->offsetExists($index)) {
            $this[$index][] = $value;
        } else {
            $this->offsetSet($index, [$value]);
        }
        return $this;
    }

    /**
     *
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function __destruct()
    {
        $this->saveCache();
    }

    /**
     * saveCache
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function saveCache()
    {
        if ($this->useCache) {
            $this->getFileStorage()->fwrite(serialize($this));
        }
    }
}