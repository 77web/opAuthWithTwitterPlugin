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
    $this->form = new BaseForm();
    
    if ($request->isMethod(sfRequest::POST))
    {
      $request->checkCSRFProtection();
      
      $member = $this->getUser()->getMember();
      $member->delete();
      //$this->sendDeleteAccountMail($member);
      $this->getUser()->setFlash('notice', '‘Þ‰ï‚ªŠ®—¹‚µ‚Ü‚µ‚½');
      $this->getUser()->logout();
      
      return $this->redirect('member/login');
    }
    
    return sfView::INPUT;
  }
}