<?php
class BasesfGuardRegisterActions extends sfActions
{
  /**
   * preExecute
   *
   * @access public
   * @return void
   */
  public function preExecute()
  {
    if($this->getUser()->isAuthenticated())
    {
      $this->redirect('@homepage');
    }
  }

  /**
   * executeRegister
   *
   * @access public
   * @return void
   */
  public function executeRegister($request)
  {
    $this->form = new sfGuardFormRegister();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('register'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        $this->sfGuardUser = new sfGuardUser();
        $this->sfGuardUser->fromArray($values, BasePeer::TYPE_FIELDNAME);
        $this->sfGuardUser->setEmailAddress($values['email_address']);
        $this->sfGuardUser->setIsActive(0);
        $this->sfGuardUser->save();

        $messageParams = array(
          'sfGuardUser' => $this->sfGuardUser,
          'password' => $values['password']
        );
        $message = $this->getComponent($this->getModuleName(), 'send_request_confirm', $messageParams);

        $mailParams = array(
          'module'  => $this->getModuleName(),
          'action'  => $this->getActionName(),
          'to'      => $this->sfGuardUser->getEmailAddress(),
          'subject' => 'Confirm Registration',
          'message' => $message
        );
        sfGuardExtraMail::send($mailParams);

        $this->getUser()->setFlash('values', $values);

        return $this->redirect('@sf_guard_do_register');
      }
    }
  }

  /**
   * executeRequest_confirm_register
   *
   * @access public
   * @return void
   */
  public function executeRequest_confirm_register()
  {
  }

  /**
   * executeRegister_confirm
   *
   * @access public
   * @return void
   */
  public function executeRegister_confirm($request)
  {
    $c = new Criteria();
    $c->add(sfGuardUserPeer::PASSWORD, $request->getParameter('key'));
    $c->add(sfGuardUserPeer::ID, $request->getParameter('id'));

 	  $sfGuardUser = sfGuardUserPeer::doSelectOne($c);

    $this->forward404Unless($sfGuardUser);

    $sfGuardUser->setIsActive(1);
    $sfGuardUser->save();

    $messageParams = array(
      'sfGuardUser' => $sfGuardUser,
    );
    $message = $this->getComponent($this->getModuleName(), 'send_complete', $messageParams);

    $mailParams = array(
      'module'  => $this->getModuleName(),
      'action'  => $this->getActionName(),
      'to'      => $sfGuardUser->getEmailAddress(),
      'subject' => 'Registration Complete',
      'message' => $message
    );
    sfGuardExtraMail::send($mailParams);

    $this->redirect('@sf_guard_register_complete?id='.$sfGuardUser->getId());
  }

  /**
   * executeRegister_complete
   *
   * @access public
   * @return void
   */
  public function executeRegister_complete()
  {
  }
}
