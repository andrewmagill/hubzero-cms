<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2013 Purdue University. All rights reserved.
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
 * @copyright Copyright 2005-2013 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

namespace Hubzero\Antispam;

use Hubzero\Antispam\Adapter;
use Hubzero\Antispam\Exception;

class Service
{
	/**
	 * Antispam adapter
	 *
	 * @var Adapter\AdapterInterface
	 */
	protected $_adapter = null;

	/**
	 * Constructor
	 *
	 * @param    Adapter\AdapterInterface $adapter
	 */
	public function __construct($adapter = null)
	{
		if (null !== $adapter) 
		{
			$this->setAdapter($adapter);
		}
	}

	/**
	 * Returns the authentication adapter
	 *
	 * The adapter does not have a default if the storage adapter has not been set.
	 *
	 * @return    Adapter\AdapterInterface|null
	 */
	public function getAdapter()
	{
		return $this->_adapter;
	}

	/**
	 * Sets the authentication adapter
	 *
	 * @param     mixed $adapter string or Adapter\AdapterInterface 
	 * @return    Service
	 */
	public function setAdapter($adapter)
	{
		if (is_string($adapter))
		{
			$invokable = '\Hubzero\Antispam\Adapter\\' . $adapter;
			if (!class_exists($invokable)) 
			{
				throw new Exception\AdapterNotFoundException(sprintf(
					'%s: failed retrieving adapter via invokable class "%s"; class does not exist',
					get_class($this) . '::' . __FUNCTION__,
					$invokable
				));
			}
			$adapter = new $invokable();
		}

		if (!($adapter instanceof Adapter\AdapterInterface))
		{
			throw new \InvalidArgumentException(JText::_('Adapter must implement ' . __NAMESPACE__ . '\AdapterInterface'));
		}

		$this->_adapter = $adapter;
		return $this;
	}

	/**
	 * Validate against the supplied adapter
	 *
	 * @param    mixed $value
	 * @return   boolean
	 * @throws   \RuntimeException
	 */
	public function isSpam($value)
	{
		if (!$adapter = $this->getAdapter()) 
		{
			throw new Exception\AdapterNotFoundException('An adapter must be set or passed prior to calling isSpam()');
		}

		$adapter->setValue($value);

		return $adapter->isSpam($value);
	}

	/**
	 * Clears the identity from persistent storage
	 *
	 * @return   void
	 */
	public function getMessages()
	{
		return $this->getAdapter()->getMessages();
	}
}
