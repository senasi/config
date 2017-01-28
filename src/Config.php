<?php

namespace Senasi\Config;

use ArrayAccess;
use Nette\Neon\Neon;


class Config extends Collection implements ArrayAccess
{
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

			throw new FileNotFoundException();
		}

		$data = Neon::decode(file_get_contents($fileName));

		if (is_array($data)) {
			$this->values = $this->init(array_merge($this->values, $data));
			return true;
		}

		return false;
	}

}
