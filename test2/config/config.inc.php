<?php
/**
 * Created by PhpStorm.
 * User: Francisco Torres
 * Date: 21/01/2018
 * Time: 11:51
 */

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

define('PDF_DIR',dirname(__DIR__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR);
define('TXT_DIR',dirname(__DIR__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'txt'.DIRECTORY_SEPARATOR);
define('RETRY_MAX', 3);
