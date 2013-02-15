<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

include dirname(__FILE__).'/../../bootstrap/database.php';

$browser->get('/')->getUser()->login(2);
$browser
  ->get('/')
  ->with('user')->begin()
    ->isAuthenticated(true)
    ->isAttribute('member_id', 2, 'opSecurityUser')
    ->hasCredential('SNSMember')
  ->end()
;

$browser
  ->setCulture('en')
  ->info('hack member/delete for user registered via opAuthWithTwitterPlugin')
  ->get('/member/delete')
  ->with('request')->begin()
    ->isParameter('module', 'member')
    ->isParameter('action', 'delete')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->debug()
  ->end()

  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'opAuthWithTwitterPlugin')
    ->isParameter('action', 'delete')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#WithTwitterMemberDeleteForm')
    ->checkElement('h3:contains("Do you delete your MySNS account?")')
    ->checkElement('input[value="Yes"]')
    ->checkElement('input[value="No"]')
  ->end()
  
  ->click('No')
  ->isForwardedTo('member', 'home')
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()

  ->get('/member/delete')
  ->followRedirect()
  ->isForwardedTo('opAuthWithTwitterPlugin', 'delete')
  
  ->click('Yes')
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
  
  ->followRedirect()
  ->isForwardedTo('member', 'login')
  ->with('user')->begin()
    ->isAuthenticated(false)
  ->end()
;

$member = Doctrine::getTable('Member')->find(2);
$browser->test()->ok(!$member, 'Member 2 is deleted.');

$browser
  ->login('sns@example.com', 'password')
  ->with('user')->isAuthenticated(true)
;

$browser
  ->info('still ordinary member/delete for non-opAuthWithTwitterPlugin user')
  ->get('member/delete')
  ->with('request')->begin()
    ->isParameter('module', 'member')
    ->isParameter('action', 'delete')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#page_member_delete')
  ->end()
;