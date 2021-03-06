<?php

/**
 * This file is part of the WarGaming API package
 *
 * (c) Vitaliy Zhuk
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace WarGaming\Api\Model;

/**
 * Collection
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Collection implements \Iterator, \ArrayAccess, \Countable, \Serializable
{
    /**
     * @var array
     */
    protected $storage;

    /**
     * Construct
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->storage = $data;
    }

    /**
     * Merge with array
     *
     * @param array|\Iterator $data
     *
     * @return Collection
     *
     * @throws \InvalidArgumentException
     */
    public function merge($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid data. Must be a array or \Traversable instance, but "%s" given.',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        foreach ($data as $key => $value) {
            $this->storage[$key] = $value;
        }

        return $this;
    }

    /**
     * Add items to collection
     *
     * @param array|\Traversable $data
     *
     * @return Collection
     *
     * @throws \InvalidArgumentException
     */
    public function addCollection($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid data. Must be a array or \Traversable instance, but "%s" given.',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        foreach ($data as $value) {
            $this->storage[] = $value;
        }

        return $this;
    }

    /**
     * Sort storage with user function
     *
     * @param callable $callback
     *
     * @throws \InvalidArgumentException
     */
    public function sort($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(sprintf(
                'Callback must be a callable, but "%s" given.',
                is_object($callback) ? get_class($callback) : gettype($callback)
            ));
        }

        uasort($this->storage, $callback);
    }

    /**
     * Get first object from collection
     *
     * @return mixed
     */
    public function getFirstObject()
    {
        if (!count($this->storage)) {
            return null;
        }

        reset($this->storage);
        list (, $value) = each($this->storage);

        return $value;
    }

    /**
     * Clear collection
     *
     * @return Collection
     */
    public function clear()
    {
        $this->storage = array();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->storage[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset ($this->storage[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->storage);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->storage);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->storage);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return key($this->storage) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->storage);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array($this->storage));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list ($this->storage) = unserialize($serialized);
    }
}
