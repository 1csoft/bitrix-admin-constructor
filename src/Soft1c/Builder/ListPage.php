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
	protected $CAdminList;

	protected $config;

	protected $fields;

	public function __construct($params = [], $config = [], $fields = [])
	{
		$this->setAdminList(new \CAdminList($params['table_id'], $params['sort']));
		$this->setFields($fields);
		$this->setConfig($config);

	}

	/**
	 * @method getList - get param list
	 * @return \CAdminList
	 */
	public function getAdminList()
	{
		return $this->CAdminList;
	}

	/**
	 * @method setList - set param List
	 * @param \CAdminList $list
	 */
	public function setAdminList(\CAdminList $list)
	{
		$this->CAdminList = $list;
	}

	public function render()
	{
//		dump($this->getFields()['core']);

	}

	public function renderFilter()
	{

	}

	/**
	 * @method getConfig - get param config
	 * @return mixed
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * @method setConfig - set param Config
	 * @param mixed $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}

	/**
	 * @method getFields - get param fields
	 * @return mixed
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @method setFields - set param Fields
	 * @param mixed $fields
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;
	}

}