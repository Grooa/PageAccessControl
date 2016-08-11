<?php

namespace Plugin\RestrictedPages;

class Event
{

	public static function ipBeforeController($data)
	{
		// Allow editing page in managemnt mode
		if (ipIsManagementState()) {
			return;
		}

		// This only works for pages
		$page = ipContent()->getCurrentPage();
		if (!$page) {
			return;
		}

		// Check restrictions
		$group = Model::getGroup($page->getId());

		if ($group !== null && !ipUser()->loggedIn()) {
			header('Location: ' . ipRouteUrl('User_login'));
			die();
		}
	}


	public static function ipFormUpdatePageSubmitted($data)
	{
		$pageId = $data[0]['pageId'];
		Model::setGroup(
			$pageId,
			isset($data[0]['usersOnly']) ? 'registered' : null
		);
	}

}
