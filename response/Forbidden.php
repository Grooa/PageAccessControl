<?php
namespace Plugin\PageAccessControl\response;

class Forbidden extends \Ip\Response\Layout
{

    public function __construct($content = null, $headers = null, $statusCode = 404)
    {
        parent::__construct($content, $headers, $statusCode);
		$this->setStatusCode(403);
        $this->setTitle('Forbidden');
    }

    public function getLayout()
    {
        if (ipThemeFile('forbidden.php') && !$this->layout) {
            return 'forbidden.php';
        }

		return parent::getLayout();
    }

}


