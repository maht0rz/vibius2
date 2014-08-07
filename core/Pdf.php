<?php

namespace vibius\core;

define('DOMPDF_ENABLE_AUTOLOAD', false);
require(dirname(__DIR__).'/vendor/dompdf/dompdf/dompdf_config.inc.php');

class Pdf extends \DOMPDF{

	public static function header(){
		header('Content-type: application/pdf');
	}

}