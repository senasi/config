<?php

namespace Senasi\Config;

class FileNotFoundException extends Exception
{
	public function __construct($fileName)
	{
		return parent::__construct('Cannot load configuration file ' . $fileName);
	}
}
