<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Twitter Login form (submit button only.)
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Mamoru Tejima
 */
class opAuthLoginFormWithTwitter extends opAuthLoginForm
{
  public function configure()
  {
    $this->setOption('is_use_remember_me', false);
    parent::configure();
  }
  
  public function setNextUri($uri)
  {
    $this->values['next_uri'] = $uri;
  }
}
