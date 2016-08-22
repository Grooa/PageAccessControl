<?php

namespace Plugin\PageAccessControl;

class Event
{

	public static function ipFormUpdatePageSubmitted($data)
	{
		if (empty($data[0]['accessPolicy'])) {
			return;
		}

		Model::setPolicy(
			$data[0]['pageId'],
			$data[0]['accessPolicy']
		);
	}

}
