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
defined('_JEXEC') or die( 'Restricted access' );

if ($this->question->isOpen() && !$this->question->isReported())
{
	$status = 'open';
}
else if ($this->question->isReported())
{
	$status = 'underreview';
}
else
{
	$status = 'closed';
}

$link = rtrim(Request::base(), '/') . '/' . ltrim(Route::url($this->question->link()), '/');
?>
	<!-- Start Header -->
	<table class="tbl-header" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td width="10%" align="left" valign="bottom" nowrap="nowrap" class="sitename">
					<?php echo Config::get('sitename'); ?>
				</td>
				<td width="80%" align="left" valign="bottom" class="tagline mobilehide">
					<span class="home">
						<a href="<?php echo Request::base(); ?>"><?php echo Request::base(); ?></a>
					</span>
					<br />
					<span class="description"><?php echo Config::get('MetaDesc'); ?></span>
				</td>
				<td width="10%" align="right" valign="bottom" nowrap="nowrap" class="component">
					Questions &amp; Answers
				</td>
			</tr>
		</tbody>
	</table>
	<!-- End Header -->

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->

	<!-- Start Message -->
	<table class="tbl-message" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td align="left" valign="bottom" style="border-collapse: collapse; color: #666; line-height: 1; padding: 5px; text-align: center;">
					A response has been posted to Question #<?php echo $this->question->get('id'); ?> by <?php echo $this->row->get('anonymous') ? Lang::txt('COM_ANSWERS_ANONYMOUS') : $this->escape(stripslashes($this->row->creator('name'))); ?>.
				</td>
			</tr>
		</tbody>
	</table>
	<!-- End Message -->

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->

	<table id="question-info" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; border: 1px solid #c8e3c2; background: #eafbe6; font-size: 0.9em; line-height: 1.6em; background-image: -webkit-gradient(linear, 0 0, 100% 100%,
										color-stop(.25, rgba(255, 255, 255, .075)), color-stop(.25, transparent),
										color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .075)),
										color-stop(.75, rgba(255, 255, 255, .075)), color-stop(.75, transparent),
										to(transparent));
	background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
									-webkit-background-size: 30px 30px;
									-moz-background-size: 30px 30px;
									background-size: 30px 30px;">
		<thead>
			<tr>
				<th colspan="2" style="font-weight: normal; border-bottom: 1px solid #c8e3c2; padding: 8px; text-align: left" align="left">
					<?php echo $this->question->subject('parsed'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="mobilehide" style="font-size: 2.5em; font-weight: bold; text-align: center; padding: 8px 30px 8px 0; vertical-align: top;" align="center" valing="top">
					<p style="display: block; border: 1px solid transparent; margin:0; padding: 1em;">?</p>
				</td>
				<td width="100%" style="padding: 8px;">
					<table width="100%" style="border-collapse: collapse; font-size: 0.9em;" cellpadding="0" cellspacing="0" border="0">
						<tbody>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Question:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left"># <?php echo $this->question->get('id'); ?></td>
							</tr>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Created:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left">@ <?php echo $this->question->created('time'); ?> on <?php echo $this->question->created('date'); ?></td>
							</tr>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Creator:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left"><?php echo $this->question->get('anonymous') ? Lang::txt('COM_ANSWERS_ANONYMOUS') : $this->escape(stripslashes($this->question->creator('name'))); ?></td>
							</tr>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Status:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left"><?php echo $status ?></td>
							</tr>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right">Tags:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left"><?php echo $this->escape($this->question->tags('string')); ?></td>
							</tr>
							<tr>
								<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Link:</th>
								<td style="text-align: left; padding: 0 0.5em;" width="100%" align="left"><a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

	<table width="100%" id="question-comments" style="border-collapse: collapse; margin: 3em 0 0 0; padding: 0" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td class="mobilehide" rowspan="2" style="font-size: 2.5em; font-weight: bold; text-align: center; vertical-align: top; padding: 0 30px 8px 0;" align="center" valign="top"><p style="display: block; border: 1px solid #c2e1e3; margin:0; padding: 1em; background-color: #e6fafb;">!</p></td>
				<th style="text-align: left;" align="left"><?php echo $this->row->get('anonymous') ? Lang::txt('COM_ANSWERS_ANONYMOUS') : $this->escape(stripslashes($this->row->creator('name'))); ?></th>
				<th class="timestamp" style="color: #999; text-align: right;" align="right"><span class="mobilehide">@ <?php echo $this->row->created('time'); ?> on <?php echo $this->row->created('date'); ?></span></th>
			</tr>
			<tr>
				<td colspan="2" style="padding: 0" cellpadding="0" cellspacing="0" border="0">
					<div style="line-height: 1.6em; margin: 1em 0; padding: 0; text-align: left;"><?php echo $this->row->content('parsed'); ?></div>
				</td>
			</tr>
		</tbody>
	</table>

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->

	<!-- Start Footer -->
	<table class="tbl-footer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td align="left" valign="bottom">
					<span><?php echo Config::get('sitename'); ?> sent this email because you were added to the list of recipients on <a href="<?php echo $link; ?>">question #<?php echo $this->question->get('id'); ?></a>. Visit our <a href="<?php echo Request::base(); ?>/legal/privacy">Privacy Policy</a> and <a href="<?php echo Request::base(); ?>/support">Support Center</a> if you have any questions.</span>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- End Footer -->