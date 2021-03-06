<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// no direct access
defined('_HZEXEC_') or die();

$clientId  = $this->filters['client_id'];
$published = $this->filters['state'];
?>
<fieldset class="batch">
	<legend><span><?php echo Lang::txt('COM_MODULES_BATCH_OPTIONS'); ?></span></legend>

	<p><?php echo Lang::txt('COM_MODULES_BATCH_TIP'); ?></p>

	<div class="grid">
		<div class="col span6">
			<div class="input-wrap">
				<?php echo Html::batch('access'); ?>
			</div>

			<div class="input-wrap">
				<?php echo Html::batch('language'); ?>
			</div>
		</div>
		<div class="col span6">
			<?php if ($published >= 0) : ?>
				<?php echo Components\Modules\Helpers\Modules::positions($clientId); ?>
			<?php endif; ?>

			<div class="input-wrap">
				<button type="submit" id="btn-batch-submit">
					<?php echo Lang::txt('JGLOBAL_BATCH_PROCESS'); ?>
				</button>
				<button type="button" id="btn-batch-clear">
					<?php echo Lang::txt('JSEARCH_FILTER_CLEAR'); ?>
				</button>
			</div>
		</div>
	</div>
</fieldset>
