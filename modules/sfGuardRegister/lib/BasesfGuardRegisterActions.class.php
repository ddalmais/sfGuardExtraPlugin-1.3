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
  
  public function executeIndex($request)
  {
    $this->form = new sfGuardFormRegister();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('register'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

//TODO: insert into db
//    $this->sfGuardUser = new sfGuardUser();
//    $this->sfGuardUser->merge($userInfo);
//    $this->sfGuardUser->setEmailAddress($userInfo['email_address']);
//    $this->sfGuardUser->setIsActive(0);
//    $this->sfGuardUser->register($userInfo);
//    $this->sfGuardUser->save();
//TODO: send mail
//        $sfGuardUser = sfGuardUserTable::retrieveByUsernameOrEmailAddress($values['username_or_email_address'], true);
//
//        $messageParams = array(
//          'sfGuardUser' => $sfGuardUser,
//        );
//        $message = $this->getComponent('sfGuardForgotPassword', 'send_request_reset_password', $messageParams);
//
//        $mailParams = array(
//          'to' => $sfGuardUser->getEmailAddress(),
//          'subject' => 'Request to reset password',
//          'message' => $message
//        );
//        sfGuardExtraMail::send($mailParams);

        return $this->redirect('@sf_guard_do_register?'.http_build_query($values));
      }
    }
  }

//  public function executeRegister()
//  {
//    $userInfo = $this->getRequestParameter('user');
//    
//    $this->sfGuardUser = new sfGuardUser();
//    $this->sfGuardUser->merge($userInfo);
//    $this->sfGuardUser->setEmailAddress($userInfo['email_address']);
//    $this->sfGuardUser->setIsActive(0);
//    $this->sfGuardUser->register($userInfo);
//    $this->sfGuardUser->save();
//    
//    $rawEmail = $this->sendEmail('sfGuardRegister', 'send_confirm_registration');
//    $this->logMessage($rawEmail, 'debug');
//    
//    $this->setFlash('notice', 'Please check your e-mail, you must confirm your registration before continuing!');
//    $this->redirect('@sf_guard_register_success');
//  }
  
  public function executeRegister_success()
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