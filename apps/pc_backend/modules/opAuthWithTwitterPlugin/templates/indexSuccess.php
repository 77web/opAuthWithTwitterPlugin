<h2>プラグイン設定</h2>
<form action="<?php echo url_for('opAuthWithTwitterPlugin/index') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('設定変更') ?>" /></td>
</tr>
</table>
</form>
<?php echo link_to("戻る", "plugin/list?type=auth") ?>
