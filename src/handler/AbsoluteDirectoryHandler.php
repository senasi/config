<?php

namespace Senasi\Config\Handler;


class AbsoluteDirectoryHandler implements HandlerInterface
{
	/**
	 * @var string
	 */
	private $baseDir;

	/**
	 * @var bool
	 */
	private $useRealPath;

	/**
	 * AbsoluteDirectoryHandler - Will prepend baseDir to all relative paths
	 *
	 * @param string $baseDir
	 * @param bool $useRealPath
	 */
	public function __construct(string $baseDir, bool $useRealPath = true)
	{
		$this->baseDir = $baseDir;
		$this->useRealPath = $useRealPath;
	}

	public function handle($value)
	{
		if (substr($value, 0, 1) !== '/') {
			$value = $this->baseDir . '/' . $value;
		}

		if ($this->useRealPath) {
			$value = realpath($value);
		}

		return $value;
	}

}
