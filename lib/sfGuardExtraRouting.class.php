<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardRouting.class.php 7636 2008-02-27 18:50:43Z fabien $
 */
class sfGuardExtraRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // forgot password
    $r->prependRoute('sf_guard_password', '/request_password', array('module' => 'sfGuardForgotPassword', 'action' => 'password'));
    $r->prependRoute('sf_guard_do_password', '/request_password/do', array('module' => 'sfGuardForgotPassword', 'action' => 'request_reset_password'));
    $r->prependRoute('sf_guard_forgot_password_reset_password', '/reset_password/:key/:id', array('module' => 'sfGuardForgotPassword', 'action' => 'reset_password'));

    // register
    $r->prependRoute('sf_guard_register', '/register', array('module' => 'sfGuardRegister', 'action' => 'index'));
    $r->prependRoute('sf_guard_do_register', '/register/do', array('module' => 'sfGuardRegister', 'action' => 'register'));
    $r->prependRoute('sf_guard_register_confirm', '/register/confirm/:key/:id', array('module' => 'sfGuardRegister', 'action' => 'register_confirm'));
    $r->prependRoute('sf_guard_register_success', '/register/success', array('module' => 'sfGuardRegister', 'action' => 'register_success'));
    $r->prependRoute('sf_guard_register_complete', '/register/complete/:id', array('module' => 'sfGuardRegister', 'action' => 'register_complete'));
  }
}
