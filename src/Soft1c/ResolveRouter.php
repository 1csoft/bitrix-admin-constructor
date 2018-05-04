<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c;

use Bitrix\Main;

class ResolveRouter
{
	/** @var Main\Request */
	protected $request;

	/**
	 * BaseRouter constructor.
	 *
	 * @param Main\Request $request
	 */
	public function __construct(Main\Request $request = null)
	{
		$this->request = $request;
	}

}