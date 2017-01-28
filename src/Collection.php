<?php

namespace Senasi\Config;

use ArrayAccess;


class Collection implements ArrayAccess
{
	/**
	 * @var array
	 */
	protected $values;

	public function __construct(array $values)
	{
		$this->values = $this->init($values);
	}

	protected function init(array $values)
	{
		return array_map(function($value) {
			if (is_array($value)) {
				$value = new self($value);
			}

			return $value;
		}, $values);
	}

	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->values);
	}

	public function offsetGet($offset)
	{
		return $this->offsetExists($offset)
			? $this->values[$offset]
			: null;
	}

	public function offsetSet($offset, $value)
	{
		throw new ReadOnlyException();
	}

	public function offsetUnset($offset)
	{
		throw new ReadOnlyException();
	}

	public function __isset($key)
	{
		return $this->offsetExists($key);
	}

	public function __get($key)
	{
		return $this->offsetGet($key);
	}

	public function __set($key ,$value)
	{
		throw new ReadOnlyException();
	}

	public function __unset($key)
	{
		throw new ReadOnlyException();
	}

}
