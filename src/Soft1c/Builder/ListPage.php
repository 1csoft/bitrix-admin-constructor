<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\Builder;


class ListPage
{
	/** @var \CAdminList */
	protected $list;

	/**
	 * ListPage constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @method getList - get param list
	 * @return \CAdminList
	 */
	public function getList()
	{
		return $this->list;
	}

	/**
	 * @method setList - set param List
	 * @param \CAdminList $list
	 */
	public function setList(\CAdminList $list)
	{
		$this->list = $list;
	}


}