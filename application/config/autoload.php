<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$autoload['libraries'] = array('session','database','encryption','Authorization_Token');
	
$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'file', 'string', 'activity');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('Model_Module','Model_Notif');
