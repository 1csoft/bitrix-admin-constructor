<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\Builder;


class ListPage implements PageRender
{
	/** @var \CAdminList */
	protected $list;

	public function __construct($params)
	{
		$this->setList(new \CAdminList($params['table_id'], $params['sort']));
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

	public function render()
	{
		// TODO: Implement render() method.
	}

	public function renderFilter()
	{


	}

}