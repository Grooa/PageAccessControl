<?php

namespace Plugin\RestrictedPages;

class Event
{

	public static function ipBeforeController($data)
	{
		$page = ipContent()->getCurrentPage();

		if (!$page) {
			return;
		}

		$pageId = $page->getId();

		if (Model::getGroup($pageId) !== null && !ipUser()->loggedIn()) {
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
