<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 07.05.2018
 */

namespace Soft1c\AdminExceptions;

use Throwable;

class ConfigurationModule extends \Exception implements AdminException
{

	public function __construct($message = "", $code = 1000, Throwable $previous = null)
	{
		$message = 'Error parsing configuration. '.$message;

		parent::__construct($message, $code, $previous);
	}

}