<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Joomla! P3P Header Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.p3p
 */
class plgSystemP3p extends \Hubzero\Plugin\Plugin
{
	public function onAfterInitialise()
	{
		// Get the header
		$header = $this->params->get('header', 'NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM');
		$header = trim($header);

		// Bail out on empty header (why would anyone do that?!)
		if (empty($header))
		{
			return;
		}

		// Replace any existing P3P headers in the response
		App::get('response')->headers->set('P3P', 'CP="' . $header . '"', true);
	}
}
