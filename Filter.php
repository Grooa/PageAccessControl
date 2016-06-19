<?php

namespace Plugin\RestrictedPages;

use \Ip\Form;
use \Ip\Form\Fieldset;
use \Ip\Form\Field;

class Filter
{

	public static function ipPagePropertiesForm($form, $info)
	{
		$form->addFieldset(
			new Fieldset('Access Control')
		);

		$form->addField(
			new Field\Checkbox(array(
				'name' => 'usersOnly',
				'label' => 'Registered only',
				'hint' => 'Only allow access to registered users',
				'value' => Model::getGroup($info['pageId']) !== null
			))
		);

		return $form;
	}

}
