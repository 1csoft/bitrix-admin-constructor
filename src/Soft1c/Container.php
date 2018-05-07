<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c;

use Bitrix\Main;
use Soft1c\AdminExceptions;
use Soft1c\Builder\PageRender;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Container extends DependencyInjection\ContainerBuilder
{
	/** @var Main\Request */
	protected $request;

	/** @var string */
	protected $moduleId;

	/** @var string */
	protected $entityType;

	/** @var Main\Entity\Base */
	protected $model;

	/** @var Main\Entity\Field [] */
	protected $fields;

	/** @var array */
	protected $configEntity = [];

	/** @var string */
	protected $entityNameAdmin;

	/** @var array|null */
	protected static $configModule = null;

	private $defaultField = [
		'property' => '',
		'title' => '',
		'type' => '',
		'primary' => false,
		'unique' => false,
		'require' => false,
		'autocomplete' => false,
		'validators' => [],
		'serialized' => false,
		'modifiers' => [
			'save' => [],
			'fetch' => [],
		],
	];

	public function __construct(ParameterBagInterface $parameterBag = null)
	{
		parent::__construct($parameterBag);

		$this->request = Main\Context::getCurrent()->getRequest();
	}

	/**
	 * @method resolve
	 * @return $this
	 * @throws AdminExceptions\ConfigurationModule
	 * @throws AdminExceptions\LoaderException
	 */
	public function resolve()
	{
		$options = new OptionsResolver();
		$options->setRequired(['_module', '_entity', '_type'])
			->setAllowedTypes('_module', 'string')
			->setAllowedTypes('_entity', 'string')
			->setAllowedTypes('_type', 'string')
			->setAllowedValues('_type', ['list', 'edit', 'simple'])
			->setDefault('_type', 'list');

		$options->setDefined(array_keys($this->request->toArray()));
		$opt = $options->resolve($this->request->toArray());

		if (!Main\Loader::includeModule($opt['_module'])){
			throw new AdminExceptions\LoaderException($opt['_module']);
		}

		$config = static::getConfig($opt['_module']);

		$this->entityNameAdmin = $opt['_entity'];
		$currentEntity = $config['entities'][$opt['_entity']];
		if (is_null($currentEntity)){
			throw new AdminExceptions\ConfigurationModule('The '.$opt['_entity'].' section not found in config file');
		}

		foreach ((array)$currentEntity['modules'] as $module) {
			if (!Main\Loader::includeModule($module))
				throw new AdminExceptions\LoaderException($module);
		}

		/** @var Main\Entity\DataManager $model */
		$model = $currentEntity['model'];
		try {
			$this->model = $model::getEntity();
//			$fields = $this->model->getFields();
		} catch (\Error $error) {
			throw new AdminExceptions\ErrorException($error->getMessage(), $error->getCode());
		}

//		dump($this->model->getFields());

		$this->entityType = $opt['_type'];
		$this->moduleId = $opt['_module'];
		$this->configEntity = $currentEntity;
		$this->buildFieldsMap($this->entityType);

		return $this;
	}

	/**
	 * @method buildFieldsMap
	 * @param string $typeEntity
	 *
	 * @return $this
	 * @throws AdminExceptions\ConfigurationModule
	 */
	private function buildFieldsMap($typeEntity = '')
	{
		if (strlen($typeEntity) == 0){
			$typeEntity = $this->entityType;
		}
		if (strlen($typeEntity) == 0){
			throw new AdminExceptions\ConfigurationModule('The entity type is not defined');
		}

		switch ($typeEntity) {
			case 'list':
			case 'edit':
				$fieldsMap = $this->configEntity[$typeEntity]['fields'];
				if (count($fieldsMap) > 0){
					foreach ($fieldsMap as $code => $value) {
						$title = $value;
						if(is_array($value)){
							$title = $value['property'];
						}

						if ($this->model->hasField($title)){

							$field = $this->model->getField($title);

							$dataField = $this->getScalarField($field);

							if(is_array($value)){
								$dataField += $value;
							}

							$this->fields['core'][$title] = $dataField;
						} else {
							$value['virtual'] = true;
							$this->fields['core'][$value['property']] = array_merge(
								$this->defaultField, $value
							);
						}

						$this->fields['map'][] = $title;
					}
				} else {
					/** @var Main\Entity\Field $field */
					foreach ($this->model->getFields() as $field) {
						$this->fields['core'][$field->getName()] = $this->getScalarField($field);
						$this->fields['map'][] = $field->getName();
					}
				}
				break;
			case 'simple':

				break;
		}

		return $this;
	}

	/**
	 * @method getScalarField
	 * @param Main\Entity\ScalarField|Main\Entity\Field $field
	 *
	 * @return array
	 */
	private function getScalarField($field)
	{
		$dataField = array_merge($this->defaultField, [
			'property' => $field->getName(),
			'title' => $field->getTitle(),
			'type' => get_class($field),
			'validators' => $field->getValidators(),
			'modifiers' => [
				'save' => $field->getSaveDataModifiers(),
				'fetch' => $field->getFetchDataModifiers(),
			],
		]);

		if($field instanceof Main\Entity\ScalarField){
			$dataField['primary'] = $field->isPrimary();
			$dataField['unique'] = $field->isUnique();
			$dataField['autocomplete'] = $field->isAutocomplete();
			$dataField['require'] = $field->isRequired();
			$dataField['serialized'] = $field->isSerialized();
			if(method_exists($field, 'getValues')){
				$dataField['values'] = $field->getValues();
			}
		}

		return $dataField;
	}

	/**
	 * @method getConfig
	 * @param string|null $moduleId
	 *
	 * @return mixed|null
	 * @throws AdminExceptions\ConfigurationModule
	 */
	public static function getConfig(string $moduleId = null)
	{
		if (is_null(self::$configModule)){
			$root = Main\Application::getDocumentRoot();
			$folder = ['/local', '/bitrix'];
			$config = null;
			foreach ($folder as $item) {
				$fileMainConfig = $root.$item.'/modules/'.$moduleId.'/config/'.$moduleId.'.yaml';
				if (file_exists($fileMainConfig)){
					try {
						$config = Yaml::parseFile($fileMainConfig);
					} catch (ParseException $e) {
						throw new AdminExceptions\ConfigurationModule($e->getMessage(), $e->getCode());
					}
					break;
				}
			}

			if (is_null($config) || count($config) == 0){
				throw new AdminExceptions\ConfigurationModule('Configuration file not found or empty');
			}

			self::$configModule = $config;
		}

		return self::$configModule;
	}

	/**
	 * @method getRequest - get param request
	 * @return Main\Request
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @method setRequest - set param Request
	 * @param Main\Request $request
	 */
	public function setRequest(Main\Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @method getModuleId - get param moduleId
	 * @return string
	 */
	public function getModuleId()
	{
		return $this->moduleId;
	}

	/**
	 * @method getEntityType - get param entityType
	 * @return string
	 */
	public function getEntityType()
	{
		return $this->entityType;
	}

	/**
	 * @method getModel - get param model
	 * @return Main\Entity\Base
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @method getFields - get param fields
	 * @return Main\Entity\Field[]
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @method setFields - set param Fields
	 * @param Main\Entity\Field[] $fields
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;
	}

	/**
	 * @method getConfigEntity - get param configEntity
	 * @return array
	 */
	public function getConfigEntity()
	{
		return $this->configEntity;
	}

	/**
	 * @method buildPage
	 * @return PageRender
	 */
	public function buildPage()
	{

		$defaultTableParams = [
			'table_id' => 'table_'.$this->moduleId.'_'.$this->entityNameAdmin,
			'form_id' => 'form_edit_'.$this->moduleId.'_'.$this->entityNameAdmin,
		];

		if($this->configEntity[$this->entityType]['table_id']){
			$defaultTableParams['table_id'] = $this->configEntity[$this->entityType]['table_id'];
		}

		if($this->configEntity[$this->entityType]['form_id']){
			$defaultTableParams['form_id'] = $this->configEntity[$this->entityType]['form_id'];
		}

		switch ($this->entityType){
			case 'list':
				$defaultTableParams['sort'] =  new \CAdminSorting(
					$defaultTableParams['table_id'],
					'ID', 'DESC'
				);

				$this->register('listPage', \Soft1c\Builder\ListPage::class)
					->setArguments([$defaultTableParams]);

				return $this->get('listPage');
			case 'edit':
				$this->register('editPage', \Soft1c\Builder\ListPage::class)
					->setArguments([$defaultTableParams]);

				return $this->get('editPage');
		}
	}

}