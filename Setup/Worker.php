<?php

namespace Plugin\PageAccessControl\Setup;

use \Ip\Internal\Plugins\Service as PluginService;


class Worker
{
	private $acPageTable;
	private $acUserTable;

	public function __construct()
	{
		$this->acPageTable = ipTable('accesscontrol_page');
		$this->acUserTable = ipTable('accesscontrol_user');
	}

    public function activate()
    {
		// Check user plugin is installed
		$plugins = PluginService::getActivePluginNames();

		if (!in_array('User', $plugins)) {
			throw new \Ip\Exception(
				'The page access control plugin depends on ' .
				'the user plugin. Install and activate the ' .
				'user plugin first.'
			);
		}

		$pageTable = ipTable('page');
		$userTable = ipTable('user');

		// Create table for additional page information
        ipDb()->execute("
			CREATE TABLE IF NOT EXISTS $this->acPageTable (

			  `pageId` int(11) NOT NULL,
			  `policy` varchar(255) NOT NULL,

			  FOREIGN KEY (`pageId`)
				  REFERENCES $pageTable (`id`)
				  ON DELETE CASCADE
				  ON UPDATE CASCADE,

			  PRIMARY KEY (`pageId`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");

		// Create table for user-page associations
        ipDb()->execute("
			CREATE TABLE IF NOT EXISTS $this->acUserTable (

			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `pageId` int(11) NOT NULL,
			  `userId` int(11) NOT NULL,

			  FOREIGN KEY (`userId`)
				  REFERENCES $userTable (`id`)
				  ON DELETE CASCADE
				  ON UPDATE CASCADE,

			  FOREIGN KEY (`pageId`)
				  REFERENCES $pageTable (`id`)
				  ON DELETE CASCADE
				  ON UPDATE CASCADE,

			  UNIQUE (`userId`, `pageId`),

			  PRIMARY KEY (`id`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");
    }

    public function deactivate()
    {
        //do nothing
    }

    public function remove()
    {
		ipDb()->execute("DROP TABLE $this->acPageTable;");
		ipDb()->execute("DROP TABLE $this->acUserTable;");
    }
}
