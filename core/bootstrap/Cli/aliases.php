<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// Create aliases for runtime
return [
	// Core
	'App'        => 'Hubzero\Facades\App',
	'Config'     => 'Hubzero\Facades\Config',
	'Request'    => 'Hubzero\Facades\Request',
	'Response'   => 'Hubzero\Facades\Response',
	'Event'      => 'Hubzero\Facades\Event',
	'Route'      => 'Hubzero\Facades\Route',
	'User'       => 'Hubzero\Facades\User',
	'Lang'       => 'Hubzero\Facades\Lang',
	'Log'        => 'Hubzero\Facades\Log',
	'Date'       => 'Hubzero\Facades\Date',
	'Plugin'     => 'Hubzero\Facades\Plugin',
	'Filesystem' => 'Hubzero\Facades\Filesystem',
	// CLI specific
	'Component'  => 'Hubzero\Facades\Component',
];
