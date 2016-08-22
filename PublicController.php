<?php
namespace Plugin\PageAccessControl;

use \Ip\Internal\Revision as Revision;
use \Ip\Internal\Content as Content;

class PublicController extends \Ip\Controller
{
    public function forbidden()
    {
        $content = null;
        $page = ipContent()->getPageByAlias('error403');

        if ($page) {
            $revision = Revision::getPublishedRevision($page->getId());
			$revisionId = $revision['revisionId'];
            $content = Content\Model::generateBlock('main', $revisionId, 0, 0);
		} else {
			$content = ipView(
				'views/forbidden.php',
				array()
			)->render();
		}

        return new response\Forbidden($content);
    }
}
