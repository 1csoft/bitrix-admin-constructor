<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\AdminExceptions;


interface AdminException
{
	/**
	 * AdminException constructor.
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct($message, $code = 0, \Throwable $previous = null);

}