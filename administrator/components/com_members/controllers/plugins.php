<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

ximport('Hubzero_Controller');

/**
 * Manage resource types
 */
class MembersControllerPlugins extends Hubzero_Controller
{
	public function execute()
	{
		$task = JRequest::getVar('task', '');
		if ($task && $task != 'manage')
		{
			JRequest::setVar('action', $task);
			JRequest::setVar('task', 'manage');
		}
		
		parent::execute();
	}
	
	/**
	 * List resource types
	 * 
	 * @return     void
	 */
	public function displayTask()
	{
		// Get configuration
		$app =& JFactory::getApplication();
		$config = JFactory::getConfig();

		// Incoming
		$this->view->filters = array();
		$this->view->filters['limit']    = $app->getUserStateFromRequest(
			$this->_option . '.plugins.limit',
			'limit',
			$config->getValue('config.list_limit'),
			'int'
		);
		$this->view->filters['start']    = $app->getUserStateFromRequest(
			$this->_option . '.plugins.limitstart',
			'limitstart',
			0,
			'int'
		);
		$this->view->filters['sort']     = trim($app->getUserStateFromRequest(
			$this->_option . '.plugins.sort',
			'filter_order',
			'ordering'
		));
		$this->view->filters['sort_Dir'] = trim($app->getUserStateFromRequest(
			$this->_option . '.plugins.sortdir',
			'filter_order_Dir',
			'ASC'
		));
		$this->view->filters['state'] = $app->getUserStateFromRequest(
			$this->_option . '.plugins.state',
			'state',
			'',
			'word'
		);
		$search = '';
		$filter_type = 'members';
		
		$db =& JFactory::getDBO();

		$this->client = JRequest::getWord( 'filter_client', 'site' );

		$where = '';
		if ($this->client == 'admin') {
			$where[] = 'p.client_id = 1';
			$client_id = 1;
		} else {
			$where[] = 'p.client_id = 0';
			$client_id = 0;
		}

		// used by filter
		if ( $filter_type != 1 ) {
			$where[] = 'p.folder = '.$db->Quote($filter_type);
		}
		if ( $search ) {
			$where[] = 'LOWER( p.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		if ( $this->view->filters['state'] ) {
			if ( $this->view->filters['state'] == 'P' ) {
				$where[] = 'p.published = 1';
			} else if ($this->view->filters['state'] == 'U' ) {
				$where[] = 'p.published = 0';
			}
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby 	= ' ORDER BY '.$this->view->filters['sort'] .' '. $this->view->filters['sort_Dir'] .', p.ordering ASC';

		// get the total number of records
		$query = 'SELECT COUNT(*)'
			. ' FROM #__plugins AS p'
			. $where
			;
		$db->setQuery( $query );
		$this->view->total = $db->loadResult();

		jimport('joomla.html.pagination');
		$this->view->pagination = new JPagination(
			$this->view->total, 
			$this->view->filters['start'], 
			$this->view->filters['limit']
		);

		$query = 'SELECT p.*, u.name AS editor, g.name AS groupname'
			. ' FROM #__plugins AS p'
			. ' LEFT JOIN #__users AS u ON u.id = p.checked_out'
			. ' LEFT JOIN #__groups AS g ON g.id = p.access'
			. $where
			. ' GROUP BY p.id'
			. $orderby
			;
		$db->setQuery( $query, $this->view->pagination->limitstart, $this->view->pagination->limit );
		$this->view->rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		
		$this->view->client = $this->client;
		$this->view->states = JHTML::_('grid.state', $this->view->filters['state']);
		$this->view->user = $this->juser;

		// Set any errors
		if ($this->getError())
		{
			$this->view->setError($this->getError());
		}

		// Output the HTML
		$this->view->display();
	}

	/**
	 * Edit a type
	 * 
	 * @return     void
	 */
	public function manageTask()
	{
		// Incoming (expecting an array)
		$plugin = JRequest::getVar('plugin', '');

		if (!$plugin)
		{
			// Redirect
			$this->setRedirect(
				'index.php?option=' . $this->_option . '&controller=' . $this->_controller,
				JText::_('Please select a plugin to manage.')
			);
		}

		// Get Releated Resources plugin
		JPluginHelper::importPlugin('members', $plugin);
		$dispatcher =& JDispatcher::getInstance();
		
		// Show related content
		$out = $dispatcher->trigger(
			'onManage', 
			array(
				$this->_option, 
				$this->_controller,
				JRequest::getVar('action', 'default')
			)
		);
		
		$this->view->html = '';
		
		if (count($out) > 0) 
		{
			foreach ($out as $o) 
			{
				$this->view->html .= $o;
			}
		}

		// Set any errors
		if ($this->getError())
		{
			$this->view->setError($this->getError());
		}

		// Output the HTML
		$this->view->display();
	}

	/**
	 * Cancel a task (redirects to default task)
	 *
	 * @return	void
	 */
	public function cancelTask()
	{
		$this->setRedirect(
			'index.php?option=' . $this->_option . '&controller=' . $this->_controller
		);
	}
}
