<?php

class BasesfGuardFormRegister extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInput(),
      'email_address' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
      'password_confirmation' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorDoctrineUnique(array('trim' => true, 'model' => 'sfGuardUser', 'column' => 'username'), array('required' => 'Your username is required.', 'invalid' => 'This username already exists. Please choose another one.')),
      'email_address' => new sfValidatorEmail(array('trim' => true), array('required' => 'Your e-mail address is required.', 'invalid' => 'The email address is invalid.')),
      'password' => new sfValidatorString(array(), array('required' => 'Your password is required.')),
      'password_confirmation' => new sfValidatorString(array(), array('Your password confirmation is required.')),
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_confirmation', array(), array('invalid' => 'The two passwords do not match')));

    $this->widgetSchema->setNameFormat('register[%s]');
  }
}
