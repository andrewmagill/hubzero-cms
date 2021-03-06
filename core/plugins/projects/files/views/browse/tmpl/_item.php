<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

require_once Component::path('com_tools') . '/models/orm/handler.php';

use \Components\Tools\Models\Orm\Handler;

$handlerBase = DS . trim($this->fileparams->get('handler_base_path', 'srv/projects/{project}/files/{file}'), DS);
if (!strstr($handlerBase, '{'))
{
	$handlerBase .= '/{project}/files/{file}';
}
$handlerBase = str_replace(
	array('{project}', '{file}'),
	array($this->model->get('alias'), $this->item->get('localPath')),
	$handlerBase
);

$me = ($this->item->get('email') == User::get('email')
	|| $this->item->get('author') == User::get('name'))  ? 1 : 0;
$when = $this->item->get('date') ? \Components\Projects\Helpers\Html::formatTime($this->item->get('date')) : 'N/A';
$subdirPath = $this->subdir ? '&subdir=' . urlencode($this->subdir) : '';

$link = Route::url($this->model->link('files') . '&action=' . (($this->item->get('converted')) ? 'open' : 'download') . $subdirPath . '&asset=' . urlencode($this->item->get('name')));

// Do not display Google native extension
$name = $this->item->get('name');
if ($this->item->get('remote'))
{
	$native = \Components\Projects\Helpers\Google::getGoogleNativeExts();
	if (in_array($this->item->get('ext'), $native))
	{
		$name = preg_replace("/." . $this->item->get('ext') . "\z/", "", $this->item->get('name'));

		// Attempt to build external URLs to Google services
		if (isset($this->params['remoteConnections']))
		{
			if (isset($this->params['remoteConnections'][$this->item->get('localPath')]))
			{
				$remote = $this->params['remoteConnections'][$this->item->get('localPath')];

				if ($remote->service == 'google')
				{
					switch ($this->item->get('ext'))
					{
						case 'gdoc':
							$link = 'https://docs.google.com/document/d/' . $remote->remote_id;
							break;
						case 'gslides':
							$link = 'https://docs.google.com/presentation/d/' . $remote->remote_id;
							break;
						case 'gsheet':
							$link = 'https://docs.google.com/spreadsheets/d/' . $remote->remote_id;
							break;
						default:
							break;
					}
				}
			}
		}
	}
}
$ext = $this->item->get('type') == 'file' ? $this->item->get('ext') : 'folder';
?>
<tr class="mini faded mline">
	<?php
	if ($this->model->access('content'))
	{
	?>
	<td>
		<?php
			$checkasset = "";
			if ($this->item->get('type') == 'folder')
			{
				$checkasset = ' dirr';
			}
			else
			{
				if ($this->item->get('untracked'))
				{
					$checkasset .= ' untracked';
				}
				if ($this->item->get('converted'))
				{
					$checkasset .= ' remote service-google';
				}
			}
		?>
		<input type="checkbox" value="<?php echo urlencode($this->item->get('name')); ?>" name="<?php echo $this->item->get('type') == 'file' ? 'asset[]' : 'folder[]'; ?>" class="checkasset js<?php echo $checkasset ?>" />
	</td>
	<?php } ?>
	<td class="middle_valign nobsp is-relative">
		<?php echo $this->item->drawIcon($ext); ?>
		<?php if ($this->item->get('type') == 'file') { ?>
			<div class="file-action-dropdown<?php echo ($handlers = Handler::getLaunchUrlsForFile($handlerBase)) ? ' hasMultiple' : ''; ?>">
				<a href="<?php echo $link; ?>" class="preview file:<?php echo urlencode($name); ?>"<?php echo $this->item->get('converted') ? ' rel="noopener noreferrer external" target="_blank"' : ''; ?>>
					<?php echo \Components\Projects\Helpers\Html::shortenFileName($name, 60); ?>
				</a>
				<?php if ($handlers && count($handlers) > 0) : ?>
					<?php foreach ($handlers as $handler) : ?>
					<a href="<?php echo Route::url($handler['url']); ?>">
						<?php echo $handler['prompt']; ?>
					</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php } else { ?>
			<a href="<?php echo Route::url($this->model->link('files') . '/&action=browse&subdir=' . urlencode($this->item->get('localPath'))); ?>" class="dir:<?php echo urlencode($name); ?>" title="<?php echo Lang::txt('PLG_PROJECTS_FILES_GO_TO_DIR') . ' ' . $name; ?>"><?php echo \Components\Projects\Helpers\Html::shortenFileName($name, 60); ?></a>
		<?php } ?>
	</td>
	<td class="shrinked middle_valign"></td>
	<td class="shrinked middle_valign"><?php echo $this->item->getSize(true); ?></td>
	<td class="shrinked middle_valign">
	<?php if (!$this->item->get('untracked')) { ?>
		<?php if ($this->item->get('type') == 'file' && $this->params['versionTracking'] == '1') { ?>
			<a href="<?php echo Route::url($this->model->link('files') . '&action=history' . $subdirPath . '&asset=' . urlencode($this->item->get('name'))); ?>" title="<?php echo Lang::txt('PLG_PROJECTS_FILES_HISTORY_TOOLTIP'); ?>"><?php echo $when; ?></a>
		<?php } else { ?>
			<?php echo $when; ?>
		<?php } ?>
	<?php } elseif ($this->item->get('untracked')) { echo Lang::txt('PLG_PROJECTS_FILES_UNTRACKED'); } ?>
	</td>
	<?php if ($this->repo->getAdapterName() == 'git'){ ?>
		<td class="shrinked middle_valign"><?php echo $me ? Lang::txt('PLG_PROJECTS_FILES_ME') : $this->item->get('author'); ?></td>
	<?php } ?>
	<td class="shrinked middle_valign nojs">
		<?php if ($this->model->access('content')) { ?>
			<a href="<?php echo Route::url($this->model->link('files') . '&action=delete' . $subdirPath . '&asset=' . urlencode($this->item->get('name'))); ?>" title="<?php echo Lang::txt('PLG_PROJECTS_FILES_DELETE_TOOLTIP'); ?>" class="i-delete">&nbsp;</a>
			<a href="<?php echo Route::url($this->model->link('files') . '&action=move' . $subdirPath . '&asset=' . urlencode($this->item->get('name'))); ?>" title="<?php echo Lang::txt('PLG_PROJECTS_FILES_MOVE_TOOLTIP'); ?>" class="i-move">&nbsp;</a>
		<?php } ?>
	</td>
	<?php if ($this->publishing) { ?>
		<td class="shrinked"></td>
	<?php } ?>
</tr>
