<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 08.05.2018
 */

namespace Soft1c\Builder;

use Bitrix\Main;

interface ListPageRender extends PageRender
{
	/**
	 * @method getFilter
	 * @param array $filter
	 *
	 * @return null|array
	 */
	public function getFilter(array $filter = []);

	/**
	 * @method getQuery
	 * @return Main\Entity\Query
	 */
	public function getQuery();

	public function fetchData();

	/**
	 * @method renderFilter
	 * @return string|false
	 */
	public function renderFilter();
}