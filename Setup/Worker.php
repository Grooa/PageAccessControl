<?php

namespace Plugin\RestrictedPages\Setup;


class Worker
{

    public function activate()
    {
        $table = ipTable('page_restriction');
		$pageTable = ipTable('page');
        ipDb()->execute("
CREATE TABLE IF NOT EXISTS $table (
  `pageId` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NULL,
  CONSTRAINT fk_restrictedPages_pageId
	  FOREIGN KEY (`pageId`)
	  REFERENCES $pageTable (`id`)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE,
  PRIMARY KEY (`pageId`, `group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
      ");
    }

    public function deactivate()
    {
        //do nothing
    }

    public function remove()
    {
		$table = ipTable('page_restriction');
		ipDb()->execute("DROP TABLE $table;");
    }
}
