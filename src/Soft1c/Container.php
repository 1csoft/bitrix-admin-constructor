<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 04.05.2018
 */

namespace Soft1c;

use Bitrix\Main;
use Soft1c\AdminExceptions\LoaderException;
use Symfony\Component\DependencyInjection;

class Container
{
	/** @var DependencyInjection\ContainerBuilder */
	protected $container;

	/** @var Main\Request */
	protected $request;

	/**
	 * @method getContainer - get param container
	 * @return DependencyInjection\ContainerBuilder
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * @method setContainer - set param Container
	 * @param DependencyInjection\ContainerBuilder $container
	 */
	public function setContainer(DependencyInjection\ContainerBuilder $container)
	{
		$this->container = $container;
	}

	public function resolve()
	{
		$moduleId = $this->request->get('moduleId');
		if(!Main\Loader::includeModule($moduleId)){
			throw new LoaderException($moduleId);
		}

		
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


}