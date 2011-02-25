<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthAdapterWithTwitter will handle authentication for OpenPNE by Twitter OAuth
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Mamoru Tejima <tejima@tejimaya.com>
 * @author     Hiroya <hiroyaxxx@gmail.com>
 */
class opAuthAdapterWithTwitter extends opAuthAdapter
{
  protected
    $authModuleName = 'WithTwitter',
    $consumerKey = null,
    $consumerSecret = null,
    $urlCallback = null,
    $urlApiRoot = null,
    $urlAuthorize = null,
    $urlAuthenticate = null;

  public function configure()
  {
    $this->consumerKey = $this->getAuthConfig('awt_consumer');
    $this->consumerSecret = $this->getAuthConfig('awt_secret');
    $this->urlCallback = $this->getRequest()->getUri();
    $this->urlApiRoot = "http://api.twitter.com/";
    $this->urlAuthorize = "https://twitter.com/oauth/authorize?oauth_token=";
    $this->urlAuthenticate = "http://twitter.com/oauth/authenticate?oauth_token=";
  }


  public function authenticate()
  {
    $result = parent::authenticate();

    // Callback from Twitter
    $callbackToken    = $this->getRequest()->getParameter('oauth_token');
    $callbackVerifier = $this->getRequest()->getParameter('oauth_verifier');

    if ($callbackToken && $callbackVerifier)
    {
      // Get user access tokens out of the session.
      $accessToken = sfContext::getInstance()->getUser()->getAttribute('accessToken');
      if (0 == strcmp($callbackToken, $accessToken['oauth_token']))
      {
        $instance = OpenPNEOAuth::getInstance($this->urlApiRoot, $this->consumerKey, $this->consumerSecret);
        $newToken = $instance->getAccessToken($callbackToken, $callbackVerifier);
        $twitterUserId = $newToken['user_id'];
        $twitterScreenName = $newToken['screen_name'];
        $twitterAccessToken = $newToken['oauth_token'];
        $twitterTokenSecret = $newToken['oauth_token_secret'];

        $line = Doctrine::getTable('MemberConfig')->findOneByNameAndValue("twitter_user_id", $twitterUserId);
        if ($line)
        {
          // 登録済み
          $member_id = (int)($line->member_id);
          $member = Doctrine::getTable('Member')->find($member_id);
        }
        else
        {
          // 新規登録
          $member = Doctrine::getTable('Member')->createPre();
          $member->setConfig('twitter_user_id', $twitterUserId);
        }
        $member->setConfig('twitter_screen_name', $twitterScreenName);
        $member->setConfig('twitter_oauth_token', $twitterAccessToken);
        $member->setConfig('twitter_oauth_token_secret', $twitterTokenSecret);
        $member->setName($twitterScreenName);
        $member->setIsActive(true);
        $member->save();
        $result = $member->getId();
      }
      $uri = sfContext::getInstance()->getUser()->getAttribute('next_uri');
      
      if($uri)
      {
        sfContext::getInstance()->getUser()->setAttribute('next_uri', null);
        $this->getAuthForm()->setNextUri($uri);
      }
      return $result;
    }
    //コールバックでは無く、最初にログインボタン押されたらこちら
    $instance = OpenPNEOAuth::getInstance($this->urlApiRoot, $this->consumerKey, $this->consumerSecret);
    $requestToken = $instance->getRequestToken($this->urlCallback);

    // Set user access tokens out of the session.
    sfContext::getInstance()->getUser()->setAttribute('accessToken', $requestToken);
    // Set current URI
    sfContext::getInstance()->getUser()->setAttribute('next_uri', $this->getAuthForm()->getValue('next_uri'));

    header('Location: '.$this->urlAuthorize.$requestToken['oauth_token']);

    exit;
  }


  public function registerData($memberId, $form)
  {
    $member = Doctrine::getTable('Member')->find($memberId);
    if (!$member)
    {
      return false;
    }

    $member->setIsActive(true);
    return $member->save();
  }


  public function isRegisterBegin($member_id = null)
  {
    opActivateBehavior::disable();
    $member = Doctrine::getTable('Member')->find((int)$member_id);
    opActivateBehavior::enable();

    if (!$member || $member->getIsActive())
    {
      return false;
    }

    return true;
  }

  public function isRegisterFinish($member_id = null)
  {
    return false;
  }

}
