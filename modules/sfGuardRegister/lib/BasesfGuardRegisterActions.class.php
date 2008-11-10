<?php
class BasesfGuardRegisterActions extends sfActions
{
  public function preExecute()
  {
    if( $this->getUser()->isAuthenticated() )
    {
      $this->redirect('@homepage');
    }
  }
  
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
        $this->sfGuardUser->merge($values);
        $this->sfGuardUser->setEmailAddress($values['email_address']);
        $this->sfGuardUser->setIsActive(0);
        $this->sfGuardUser->save();

        $messageParams = array(
          'sfGuardUser' => $this->sfGuardUser,
        );
        $message = $this->getComponent('sfGuardRegister', 'send_request_confirm_register', $messageParams);

        $mailParams = array(
          'to' => $this->sfGuardUser->getEmailAddress(),
          'subject' => 'Register confirmation',
          'message' => $message
        );
        sfGuardExtraMail::send($mailParams);

        return $this->redirect('@sf_guard_do_register?'.http_build_query($values));
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
  
  public function executeRegister_confirm()
  {
    $params = array($this->getRequestParameter('key'), $this->getRequestParameter('id'));

    $query = new Doctrine_Query();
    $query->from('sfGuardUser u')->where('u.password = ? AND u.id = ?', $params)->limit(1);
    
    $this->sfGuardUser = $query->execute()->getFirst();
    $this->sfGuardUser->setIsActive(1);
    $this->sfGuardUser->confirm();
    $this->sfGuardUser->save();
    
    $this->forward404Unless($this->sfGuardUser);
    
    $rawEmail = $this->sendEmail('sfGuardRegister', 'send_register_complete');
    $this->logMessage($rawEmail, 'debug');
    
    $this->setFlash('notice', 'You have successfully confirmed your registration!');
    $this->redirect('@sf_guard_register_complete?id='.$this->sfGuardUser->getId());
  }
  
//  public function executeRegister_complete()
//  {
//    
//  }
  
//  public function handleErrorRegister()
//  {
//    $this->setFlash('error', 'An error occurred with your registration, please try again!');
//    $this->forward('sfGuardRegister', 'index');
//  }

  public function executeSend_confirm_registration()
  {
    $this->sfGuardUser = sfGuardUserTable::retrieveByUsernameOrEmailAddress($this->getRequestParameter('user[username]'), false);
    
    $mail = new sfMail();
    $mail->setContentType('text/html');
    $mail->setSender(sfConfig::get('app_outgoing_emails_sender'));
    $mail->setFrom(sfConfig::get('app_outgoing_emails_from'));
    $mail->addReplyTo(sfConfig::get('app_outgoing_emails_reply_to'));
    $mail->addAddress($this->sfGuardUser->getEmailAddress());
    $mail->setSubject('Confirm Registration');
    
    $this->mail = $mail;
  }

  public function executeSend_register_complete()
  {
    $params = array($this->getRequestParameter('key'), $this->getRequestParameter('id'));
    
    $query = new Doctrine_Query();
    $query->from('sfGuardUser u')->where('u.password = ? AND u.id = ?', $params)->limit(1);
    
    $this->sfGuardUser = $query->execute()->getFirst();
    
    $mail = new sfMail();
    $mail->setContentType('text/html');
    $mail->setSender(sfConfig::get('app_outgoing_emails_sender'));
    $mail->setFrom(sfConfig::get('app_outgoing_emails_from'));
    $mail->addReplyTo(sfConfig::get('app_outgoing_emails_reply_to'));
    $mail->addAddress($this->sfGuardUser->getEmailAddress());
    $mail->setSubject('Registration Complete');

    $this->mail = $mail;
  }
}