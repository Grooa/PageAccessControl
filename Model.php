<?php

namespace Plugin\RestrictedPages;

class Model
{

	public static function getGroup($pageId)
	{
		$row = ipDb()->selectRow(
			'page_restriction',
			'`group`',
			array('pageId' => $pageId)
		);

		if (!$row) {
			return null;
		}

		return $row['group'];
	}

	/**
	 * Set the groups that can access the given page.
	 */
	public static function setGroup($pageId, $group)
	{
		if ($group === null) {
			ipDb()->delete(
				'page_restriction',
				array('pageId' => $pageId)
			);
		} else if (self::getGroup($pageId)) {
			ipDb()->update(
				'page_restriction',
				array('group' => $group),
				array('pageId' => $pageId)
			);
		} else {
			ipDb()->insert(
				'page_restriction',
				array(
					'group' => $group,
					'pageId' => $pageId
				)
			);
		}
	}

}
