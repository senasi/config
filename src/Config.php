<?php

namespace Senasi\Config;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Nette\Neon\Neon;
use Senasi\Config\Handler\HandlerInterface;


class Config extends Collection implements ArrayAccess, Countable, IteratorAggregate
{
	/**
	 * @var [HandlerInterface, string][]
	 */
	private $handlers = [];

	/**
	 * Create new config object, optionally set defaults
	 *
	 * @param array $defaults
	 */
	public function __construct(array $defaults = [])
	{
		return parent::__construct($defaults);
	}

	/**
	 * @param HandlerInterface $handler
	 * @param array|null $applyKeys - null to apply to all keys
	 */
	public function pushHandler(HandlerInterface $handler, array $applyKeys = null)
	{
		$this->handlers[] = [$handler, $applyKeys];
	}

	/**
	 * Load configuration from given file. Throw Exception if strict = true and file does not exist
	 *
	 * @param string $fileName
	 * @param bool $strict
	 * @return bool
	 * @throws FileNotFoundException
	 */
	public function load(string $fileName, $strict = true)
	{
		if (!is_file($fileName)) {
			if (!$strict) {
				return false;
			}

			throw new FileNotFoundException($fileName);
		}

		$data = Neon::decode(file_get_contents($fileName));

		if (is_array($data)) {
			$this->values = $this->init(array_merge($this->values, $data));
			return true;
		}

		return false;
	}

	protected function init(array $values)
	{
		array_walk($values, function(&$value, $key) {
			// foreach ($this->handlers as [$handler, $applyKeys]) { // TODO: this is so php 7.1
			foreach ($this->handlers as list($handler, $applyKeys)) {
				if (!is_array($value) && in_array($key, $applyKeys)) {
					$value = $handler->handle($value);
				}
			}
		});

		return parent::init($values);
	}

}
