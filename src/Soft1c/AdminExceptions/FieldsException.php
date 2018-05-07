<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 07.05.2018
 */

namespace Soft1c\AdminExceptions;


class FieldsException extends \Exception implements AdminException
{

	/**
	 * FieldsException constructor.
	 *
	 * @param $message
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct($message, $code = 2000, \Throwable $previous = null)
	{
		$message = 'Error fields. '.$message;

		parent::__construct($message, $code, $previous);
	}
}