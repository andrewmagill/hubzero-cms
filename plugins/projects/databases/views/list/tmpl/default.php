<?php
/**
 * @package		HUBzero CMS
 * @author		Sudheera R. Fernando <sudheera@xconsole.org>
 * @copyright	Copyright 2012-2013 by Purdue Research Foundation, West Lafayette, IN 47906
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 *
 * Copyright 2012-2013 by Purdue Research Foundation, West Lafayette, IN 47906.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License,
 * version 3 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();

$document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js');
$document->addStyleSheet('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css');

?>
<div id="prj-db-list">
	<div class="addnew" style="float: right;"><a href="/projects/<?=$this->project->alias?>/databases/create#content"><?php echo JText::_('PLG_PROJECTS_DATA_START'); ?></a></div>
	<div id="plg-header">
		<h3 class="databases">Databases</h3>
	</div>
	<div id="confirm-file-delete" title="Delete Project Database" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0; color: red;"></span>
		Confirm database deletion.
	</p>
	</div>
	<?php if (count($this->list) > 0 ) { ?>
	<table class="listing">
		<thead>
			<tr>
				<th style="width: 40%;">Title</th>
				<th>Source File</th>
				<th>Created On</th>
				<th>Created By</th>
				<th>Recreate</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->list as $r):
				if ($r['source_dir'] != '')
				{
					$full_path = htmlspecialchars($r['source_dir'] . DS . $r['source_file']);
				}
				else
				{
					$full_path = htmlspecialchars($r['source_file']);
				}

				$file_url = '/projects/' . $this->project->alias
					. '/files/?action=download&case=files&subdir='
					. trim($r['source_dir'], '/') . '&file=' . $r['source_file'];

				$file_name = '<a href="' . $file_url . '">' . $r['source_file'] . '</a>';

				$recreate = '<a href="/projects/' . $this->project->alias
					. '/databases/create/?db_id=' . $r['id']
					. '" class="re-create-db">Recreate</a>';

				$file_extra = 'title="' . $full_path . '"';

				if ($r['source_revision'] != $r['source_revision_curr'])
				{
					if ($r['source_available'])
					{
						$file_extra = 'class="file-updated" title="The file has changed, modified ' . $r['source_revision_date'] . '"';
					}
					else
					{
						$file_extra = 'class="file-deleted" title="The file [' . $full_path . '] has been removed or renamed ' . $r['source_revision_date'] . '"';
						$file_name = '<span style="color: #ddd; cursor: not-allowed;">' . $r['source_file'] . '</span>';
						$recreate = '<span title="The original file has been removed or renamed, please restore the file to enable this functionality" style="color: #ddd; cursor: not-allowed;">Recreate<span>';
					}
				}
		?>
			<tr class="mini faded">
				<td title="<?=htmlspecialchars($r['description']);?>" data-db-title="<?=htmlspecialchars($r['title']);?>"  data-db-id="<?=$r['id'];?>">
					<a target="_blank" href="/<?=$this->dataviewer?>/spreadsheet/<?=$r['database_name']?>/"><?=$r['title']?></a>
					<span class="db-update" title="Click to edit the Title & Description"></span>
				</td>
				<td <?=$file_extra?>>
					<?=$file_name?>
				</td>
				<td>
					<?=$r['created']?>
				</td>
				<td>
					<a target="_blank" href="/members/<?=$r['created_by']?>"><?=$r['name']?></a>
				</td>
				<td>
					<?=$recreate?>
				</td>
				<td>
					<a href="/projects/<?=$this->project->alias?>/databases/delete/?db_id=<?=$r['id']?>" class="delete-db">Delete</a>
				</td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
	<?php
	}
	else {
		echo ('<p class="noresults">'.JText::_('PLG_PROJECTS_DATA_NO_DATA_FOUND').' <span class="addnew"><a href="'.JRoute::_('index.php?option='.$this->option.a.'active=databases'.a. 'alias=' . $this->project->alias . a . 'action=create#content').'" >'.JText::_('PLG_PROJECTS_DATA_START').'</a></span></p>');
	} ?>
</div>
<div id="prj-db-update-dialog" title="Update Title & Description" style="display: none;">
	<form id="prj-db-update-form" method="post" action="<?=JRoute::_('index.php?option=' . $this->option . a . 'id=' . $this->project->id . a . 'active=databases' . a . 'action=update')?>">
		<input type="hidden" name="db_id" />
		<label for="db_title" >Title:</label><br />
		<input type="text" name="db_title" style="width: 550px;" /><br /><br />
		<label for="db_description" >Description:</label><br />
		<textarea type="text" name="db_description" style="width: 550px; height: 130px;"></textarea><br /><br />
	</form>
</div>
