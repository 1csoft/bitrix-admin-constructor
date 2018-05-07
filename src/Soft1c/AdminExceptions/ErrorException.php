<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 07.05.2018
 */

namespace Soft1c\AdminExceptions;

use Throwable;

class ErrorException extends \Error implements AdminException
{
	public function __construct(string $message = "", int $code = -1, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}