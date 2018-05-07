<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c\Builder;

use Bitrix\Main;
use Soft1c\Container;

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

	public function initFilter()
	{
		return [];
	}

	public function render()
	{
		$fields = Container::getInstance()->getFields();
		$filter = $this->initFilter();

		$model = Container::getInstance()->getModel();
		$query = new Main\Entity\Query($model);

		foreach ($fields['core'] as $code => $field) {
			if($field['reference']){
				$alias = $field['reference']['FROM'].'_REF_'.$field['reference']['TO'];
				$query->addSelect($field['reference']['FROM'].'.'.$field['reference']['TO'], $alias);
			} else {
				$query->addSelect($field['property'], $code);
			}
		}

	}

	public function renderFilter()
	{


	}

}
