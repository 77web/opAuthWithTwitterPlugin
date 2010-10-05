<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthConfigFormOpenID represents a form to configuration.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Mamoru Tejima <tejima@tejimaya.com>
 * @author     Hiroya <hiroyaxxx@gmail.com>
 */
class opAuthConfigFormWithTwitter extends opAuthConfigForm
{

  public function setup()
  {
    opAuthConfigForm::setup();
    if ($w = $this->getWidget('awt_consumer'))
    {
      $w->setAttribute('size', 60);
    }
    if ($w = $this->getWidget('awt_secret'))
    {
      $w->setAttribute('size', 60);
    }
  }
}
