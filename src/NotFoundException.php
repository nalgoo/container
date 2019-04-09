<?php

namespace Nalgoo\Container;

use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
	public function __construct($message = '', $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
