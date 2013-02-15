<?php

class opAuthWithTwitterPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('op_action.pre_execute_member_delete', array($this, 'listenToMemberDelete'));
  }
  
  public function listenToMemberDelete(sfEvent $event)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    if ($member->getConfig('twitter_user_id') && !$member->getConfig('password'))
    {
      $action = $event['actionInstance'];
      
      $action->redirect('opAuthWithTwitterPlugin/delete');
    }
  }
}