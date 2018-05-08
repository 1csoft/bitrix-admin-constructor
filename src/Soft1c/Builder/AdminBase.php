<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 08.05.2018
 */

namespace Soft1c\Builder;

use Bitrix\Main;
use Soft1c\Container;
use Symfony\Component\DependencyInjection\Reference;
use Soft1c\ContainerBuilder;

class AdminBase
{
	/** @var Main\Entity\Query */
	protected $queryEntity = null;

	/** @var string  */
	private $typeEntity = 'list';

	/** @var Container  */
	protected $container;

	/** @var array  */
	protected $defaultParams = [];

	/** @var array */
	protected $fields;

	/**
	 * AdminBase constructor.
	 *
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->typeEntity = $this->container->getEntityType();

		if($this->container->getEntity() instanceof Main\Entity\Base){
			$this->getQuery();
		}

		$this->defaultParams = $this->container->getConfigEntity()[$this->typeEntity]['default_params'];
		$this->fields = $this->container->getFields()['core'];
	}

	/**
	 * @method getQuery
	 * @return Main\Entity\Query
	 */
	public function getQuery()
	{
		if(is_null($this->queryEntity))
			$this->queryEntity = new Main\Entity\Query($this->container->getEntity());

		return $this->queryEntity;
	}

	public function getFilter($filter = [])
	{

	}

	public function showList()
	{
		/** @var ListPage $adminList */
		$adminList = ContainerBuilder::getInstance()->get('admin.list');

		$adminList->render();
	}

	public function showForm()
	{

	}

	public function showSimple()
	{

	}

	public function render()
	{
//		switch ($this->getTypeEntity()){
//			case 'list':
//		}
	}

	/**
	 * @method getTypeEntity - get param typeEntity
	 * @return string
	 */
	public function getTypeEntity()
	{
		return $this->typeEntity;
	}

	/**
	 * @method setTypeEntity - set param TypeEntity
	 * @param string $typeEntity
	 */
	public function setTypeEntity($typeEntity)
	{
		$this->typeEntity = $typeEntity;
	}

	/**
	 * @method getDefaultParams - get param defaultParams
	 * @return array
	 */
	public function getDefaultParams()
	{
		return $this->defaultParams;
	}

	/**
	 * @method getFields - get param fields
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @method setFields - set param Fields
	 * @param array $fields
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;
	}
}