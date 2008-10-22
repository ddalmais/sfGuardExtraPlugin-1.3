<?php

class BasesfGuardFormRegister extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'user_username' => new sfWidgetFormInput(),
      'user_email_address' => new sfWidgetFormInput(),
      'user_password' => new sfWidgetFormInputPassword(),
      'user_password_confirmation' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'user_username' => new sfValidatorDoctrineUnique(array('trim' => true, 'class' => 'sfGuardUser', 'column' => 'username'), array('required' => 'Your username is required.', 'invalid' => 'This username already exists. Please choose another one.')),
      'user_email_address' => new sfValidatorEmail(array('trim' => true), array('required' => 'Your e-mail address is required.', 'invalid' => 'The email address is invalid.')),
      'user_password' => new sfValidatorString(array(), array('required' => 'Your password is required.')),
      'user_password_confirmation' => new sfValidatorString(array(), array('Your password confirmation is required.')),
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('user_password', sfValidatorSchemaCompare::EQUAL, 'user_password_confirmation', array(), array('invalid' => 'The two passwords do not match')));

    $this->widgetSchema->setNameFormat('register[%s]');
  }
}
