<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 07.05.2018
 */

namespace Soft1c\EntityFields;

use Soft1c\AdminExceptions\FieldsException;
use Soft1c\Parameters;

class BaseField
{

	protected $name;

	protected $options = [];

	public function __construct(string $name, $options = [])
	{
		if(strlen($name) == 0){
			throw new FieldsException('Parameter $name is empty');
		}
		$this->name = $name;

		$this->options = new Parameters((array)$options);
	}
}