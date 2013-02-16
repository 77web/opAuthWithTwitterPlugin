<?php

/**
 * opAuthWithTwitterPlugin actions
 * 
 * @package OpenPNE
 * @subpackage opAuthWithTwitterPlugin
 * @auther Hiromi Hishida<info@77-web.com>
 */
class opAuthWithTwitterPluginActions extends sfActions
{
  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getUser()->getMember()->getConfig('password'))
    {
      return $this->redirect('member/delete');
    }
    $this->form = new sfForm();
    
    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request);
      if ($this->form->isValid())
      {
        $member = $this->getUser()->getMember();
        $member->delete();
        //$this->sendDeleteAccountMail($member);
        $this->getUser()->setFlash('notice', '�މ�������܂���');
        $this->getUser()->logout();
        
        return $this->redirect('member/login');
      }
    }
    
    return sfView::INPUT;
  }
}