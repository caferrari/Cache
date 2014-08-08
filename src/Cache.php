<?php

namespace Ferrari\Cache;

use \Doctrine\Common\Cache\Cache as CacheInterface;

class Cache implements \ArrayAccess, CacheInterface
{

    private $driver;

    public function __construct(CacheInterface $driver)
    {
        $this->driver = $driver;
    }

    public function fetch($id)
    {
        return $this->driver->fetch($id);
    }

    public function contains($id)
    {
        return $this->driver->contains($id);
    }

    public function save($id, $data, $ttl = 0)
    {
        return $this->driver->save($id, $data, $ttl);
    }

    public function delete($id)
    {
        return $this->driver->delete($id);
    }

    public function getStats()
    {
        return $this->driver->getStats();
    }

    public function get($id, callable $callback = null)
    {

        if ($this->contains($id)) {
            return $this->driver->fetch($id);
        }

        if (null === $callback) {
            return false;
        }

        $data = $callback();

        $this->save($id, $data);

        return $data;
    }

    public function offsetExists($id)
    {
        return $this->contains($id);
    }

    public function offsetGet($id)
    {
        return $this->fetch($id);
    }

    public function offsetSet($id, $data)
    {
        return $this->save($id, $data);
    }

    public function offsetUnset($id)
    {
        return $this->delete($id);
    }
}
