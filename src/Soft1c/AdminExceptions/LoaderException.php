<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\AdminExceptions;

use Throwable;

class LoaderException extends \Exception implements AdminException
{
	/**
	 * LoaderException constructor.
	 *
	 * @param string $module
	 * @param int $code
	 * @param Throwable|null $previous
	 *
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function __construct($module = "", $code = 0, Throwable $previous = null)
	{
		$message = 'the module '.$module.' is not installed';
		throw new \Bitrix\Main\LoaderException($message, $code, $previous);
	}

}