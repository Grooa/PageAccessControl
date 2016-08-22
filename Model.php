<?php

namespace Plugin\PageAccessControl;

class Model
{

	/**
	 * Get the policy for a page.
	 */
	public static function getPolicy($pageId)
	{
		$row = ipDb()->selectRow(
			'accesscontrol_page',
			'policy',
			array('pageId' => $pageId)
		);
	}


	/**
	 * Set the policy for a page.
	 */
	public static function setPolicy($pageId, $policy)
	{
		if ($policy == 'inherit') {
			ipDb()->delete('accesscontrol_page', array('pageId' => $pageId));
		}

		ipDb()->upsert(
			'accesscontrol_page',
			array('pageId' => $pageId),
			array('policy' => $policy)
		);
	}


	/**
	 * Check if a user can access a page.
	 */
	public static function canAccess($user, $pageId) {
		$pageTable = ipTable('page');
		$acPageTable = ipTable('accesscontrol_page');

		$page = ipDb()->fetchRow(
			"
				select $pageTable.`parentId`, $acPageTable.`policy`
				from $pageTable
				inner join $acPageTable
					on $pageTable.`id` = $acPageTable.`pageId`
				where $pageTable.`id` = :pageId
			",
			array('pageId' => $pageId)
		);

		$policy = $page['policy'];
		$parentId = $page['parentId'];

		// Walk up tree if no information is found
		if (!$policy && $parentId) {
			return self::canAccess($user, $parentId);
		}

		if (
			!$policy ||
			$policy == 'none' ||
			$policy == 'registered' && $user->isLoggedIn()
		) {
			return true;
		}

		// Check explicit access
		if (!$user->isLoggedIn()) {
			return false;
		}

		$acUserTable = ipTable('accesscontrol_user');
		return ipDb()->fetchValue(
			"
				select count(*)
				from $acUserTable
				where `pageId` = :pageId and `userId` = :userId
			",
			array('pageId' => $pageId, 'userId' => $user->userId())
		);

	}
}
