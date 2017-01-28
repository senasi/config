<?php

namespace Senasi\Config;


class ReadOnlyException extends Exception
{
    public function __construct()
    {
        return parent::__construct('Configuration values are read-only');
    }
}
