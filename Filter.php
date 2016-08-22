<?php

namespace Plugin\PageAccessControl;

use \Ip\Form;
use \Ip\Form\Fieldset;
use \Ip\Form\Field;

class Filter
{

	public static function ipPagePropertiesForm($form, $info)
	{
		$pageId = $info['pageId'];

		$form->addFieldset(
			new Fieldset('Access Control')
		);

		$form->addField(
			new Field\Select(array(
				'name' => 'accessPolicy',
				'label' => 'Access policy',
				'hint' => 'Select who should have access to this page',
				'values' => array(
					array('inherit', 'Inherit from parent page (default)'),
					array('unrestricted', 'Unrestricted'),
					array('registered', 'Registered users only'),
					array('explicit', 'Users with explicit permission')
				),
				'value' => Model::getPolicy($pageId)
			))
		);

		$userPanelUrl = ipActionUrl(array('aa' => 'User'));
		$form->addField(
			new Field\Info(array(
				'label' => 'Tip',
				'html' => "
						You can explicitly give a user permission from the
						<a href=\"$userPanelUrl\">
						user panel
						</a>.
					"
			))
		);

		return $form;
	}


	public static function User_adminGridConfig($config)
	{
		// Fetch pages with access control
		$pageTable = ipTable('page');
		$acPageTable = ipTable('accesscontrol_page');

		$pages = ipDb()->fetchAll("
			select $pageTable.`id`, $pageTable.`title`
			from $pageTable
			inner join $acPageTable
			on $pageTable.`id` = $acPageTable.`pageId`
			where $acPageTable.`policy` = 'explicit'
		");

		// Create options for select
		$values = array_map(
			function ($page) {
				return array($page['id'], $page['title']);
			},
			$pages
		);


		// Alter user grid config
		$config['fields'][] = array(
			'label' => 'Permissions',
			'type' => 'Grid',
			'field' => 'userId',
			'config' => array(
				'title' => 'Permissions',
				'connectionField' => 'userId',
				'table' => 'accesscontrol_user',
				'fields' => array(
					array(
						'label' => 'Page',
						'field' => 'pageId',
						'type' => 'Select',
						'values' => $values
					)
				)
			)
		);

		return $config;
	}

}
