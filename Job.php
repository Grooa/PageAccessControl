<?php

namespace Plugin\PageAccessControl;

class Job
{

	public static function ipRouteAction($info)
	{
		if (ipAdminId() || $info['routeLanguage'] == null) {
            return null;
        }

		// Get page id
		$pageId = null;

        if ($info['relativeUri'] == '') {
            $pageId = ipContent()->getDefaultPageId();
        } else {
			$pageId = \Ip\Internal\Pages\Service::getPageByUrl(
				ipContent()->getCurrentLanguage()->getCode(),
				$info['relativeUri']
			)['id'];
        }

		if (!$pageId) {
			return null;
		}

		// Check restrictions
		if (Model::canAccess(ipUser(), $pageId)) {
			return null;
		}

		return array(
			'plugin' => 'PageAccessControl',
			'controller' => 'PublicController',
			'action' => 'forbidden'
		);
	}
}
