<?php if($message) : ?>
<div class="mor alert success">
<p><?php print($message); ?></p>
</div>
<?php endif;

	?>
<?php if($settings_form) : ?>

<?= form_open(
'C=addons_extensions&M=extension_settings&file=mx_auto_password',
'',
array("file" => "mx_auto_password")
);
?>
							
<table class="mainTable padTable" id="event_table" border="0" cellpadding="0" cellspacing="0">
<tr class="header" >
<th colspan="3"><?= lang('password_settings')?></th>
</tr>
<tbody>
<tr style="width: 33%;">
<td><?=lang('enable_auto_password')?></td>
<td><input name="<?=$input_prefix;?>[auto_password]" value="y"  id="auto_password_y" label="yes" type="radio" <?=((isset($settings['auto_password'])) ? (($settings['auto_password'] == "y") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="auto_password_y">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?=$input_prefix;?>[auto_password]" value="n" id="auto_password_n" label="no" type="radio" <?=((isset($settings['auto_password'])) ? (($settings['auto_password'] == "n") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="auto_password_n">No</label></td>
</tr>
<tr style="width: 33%;">
<td><?=lang('enable_cp_auto_password')?></td>
<td><input name="<?=$input_prefix;?>[auto_cp_password]" value="y"  id="auto_cp_password_y" label="yes" type="radio" <?=((isset($settings['auto_cp_password'])) ? (($settings['auto_cp_password'] == "y") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="auto_cp_password_y">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?=$input_prefix;?>[auto_cp_password]" value="n" id="auto_password_n" label="no" type="radio" <?=((isset($settings['auto_cp_password'])) ? (($settings['auto_cp_password'] == "n") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="auto_cp_password_n">No</label></td>
</tr>

<tr style="width: 33%;">
<td><?=lang('length')?></td>
<td><input dir="ltr" style="width: 100%;" name="<?=$input_prefix;?>[pass_length]" id="pass_length" value="<?=((isset($settings['pass_length'])) ? $settings['pass_length'] : '' );?>" size="20" maxlength="256" class="input" type="text"></td>
</tr>
<tr style="width: 33%;">
<td style="width: 33%;"><?=lang('use_caps')?></td>
<td><input name="<?=$input_prefix;?>[use_caps]" value="y"  id="use_caps_y" label="yes" type="radio" <?=((isset($settings['use_caps'])) ? (($settings['use_caps'] == "y") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_caps_y">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?=$input_prefix;?>[use_caps]" value="n" id="use_caps_n" label="no" type="radio" <?=((isset($settings['use_caps'])) ? (($settings['use_caps'] == "n") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_caps_n">No</label></td>	
</tr>

<tr style="width: 33%;">
<td style="width: 33%;"><?=lang('use_numeric')?></td>
<td><input name="<?= $input_prefix;?>[use_numeric]" value="y"  id="use_numeric_y" label="yes" type="radio" <?=((isset($settings['use_numeric'])) ? (($settings['use_numeric'] == "y") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_numeric_y">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?=  $input_prefix;?>[use_numeric]" value="n" id="use_numeric_n" label="no" type="radio" <?=((isset($settings['use_numeric'])) ? (($settings['use_numeric'] == "n") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_numeric_n">No</label></td>	
</tr>

<tr style="width: 33%;">
<td style="width: 33%;"><?=lang('use_specials')?></td>
<td><input name="<?= $input_prefix;?>[use_specials]" value="y"  id="use_specials_y" label="yes" type="radio" <?=((isset($settings['use_specials'])) ? (($settings['use_specials'] == "y") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_specials_y">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?=$input_prefix;?>[use_specials]" value="n" id="use_specials_n" label="no" type="radio" <?=((isset($settings['use_specials'])) ? (($settings['use_specials'] == "n") ? 'checked="checked"' : ''): '' );?>>&nbsp;<label for="use_specials_n">No</label></td>	
</tr>



</tbody>
</table>

<p class="centerSubmit"><input name="edit_field_group_name" value="<?= lang('save_extension_settings'); ?>" class="submit" type="submit"></p>

<?= form_close(); ?>

<?php endif; ?>
