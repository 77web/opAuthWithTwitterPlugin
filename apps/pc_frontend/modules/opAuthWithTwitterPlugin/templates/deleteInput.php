<?php

$options = array(
  'yes_url' => url_for('opAuthWithTwitterPlugin/delete'),
  'no_url' => url_for('@homepage'),
  'title' => __('Do you delete your %1% account?', array('%1%' => $op_config['sns_name'])),
);

op_include_yesno('WithTwitterMemberDeleteForm', $form, $form, $options);