<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\Builder;

use Bitrix\Main;
use Soft1c\Container;

class ListPage implements ListPageRender
{
	/** @var \CAdminList */
	protected $list;

	/** @var AdminBase */
	protected $adminBase;

	/** @var \CAdminSorting */
	protected $CAdminSorting;

	public function __construct(AdminBase $adminBase)
	{
		$this->adminBase = $adminBase;
		$params = $this->adminBase->getDefaultParams();
		$this->CAdminSorting = new \CAdminSorting($params['table_id']);
		foreach ($params['sort'] as $code => $order) {
			$this->CAdminSorting->by_initial = $code;
			$this->CAdminSorting->order_initial = $order;
			break;
		}

		$this->setList(new \CAdminList($params['table_id'], $this->CAdminSorting));
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

	/**
	 * @method getFilter
	 * @param array $filter
	 *
	 * @return null|array
	 */
	public function getFilter(array $filter = [])
	{
		// TODO: Implement getFilter() method.
	}

	/**
	 * @method getQuery
	 * @return Main\Entity\Query
	 */
	public function getQuery()
	{
		$this->adminBase->getQuery()
			->addOrder($this->CAdminSorting->by_initial, $this->CAdminSorting->order_initial);

		return $this->adminBase->getQuery();
	}

	public function fetchData()
	{
		foreach ($this->adminBase->getFields() as $code => $field) {
//			if($field['virtual'])
//				continue;

			if(!is_array($field['reference'])){
				$this->getQuery()->addSelect($code, $field['property']);
			} else {
				$this->getQuery()->addSelect(
					$field['reference']['FROM'].'.'.$field['reference']['TO'],
					$field['reference']['FROM'].'_REF_'.$field['reference']['TO']
				);
			}
		}

//		return $this->getQuery()->setLimit(50)->exec();
	}

	/**
	 * @method renderFilter
	 * @return string|false
	 */
	public function renderFilter()
	{
		// TODO: Implement renderFilter() method.
	}

	public function render()
	{
		$oList = $this->fetchData();
	}

}
