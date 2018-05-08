<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 08.05.2018
 */

namespace Soft1c;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContainerBuilder extends DependencyInjection\ContainerBuilder
{
	/** @var ContainerBuilder */
	private static $instance = null;

	public function __construct(ParameterBagInterface $parameterBag = null)
	{
		parent::__construct($parameterBag);
		static::setInstance($this);
		$this->register('ContainerBuilder', static::getInstance());
	}


	/**
	 * @method getInstance - get param instance
	 * @return ContainerBuilder
	 */
	public static function getInstance()
	{
		if(is_null(static::$instance))
			static::$instance = new static();

		return static::$instance;
	}

	/**
	 * @method setInstance - set param Instance
	 * @param ContainerBuilder $instance
	 */
	public static function setInstance(ContainerBuilder $instance)
	{
		static::$instance = $instance;
	}

}