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
      'username' => new sfValidatorAnd(array(
        new sfValidatorString(array('trim' => true), array('required' => 'Your username is required.')),
        new sfValidatorPropelUnique(array('trim' => true, 'model' => 'sfGuardUser', 'column' => 'username'), array('invalid' => 'This username already exists. Please choose another one.')),
      # see ticket http://trac.symfony-project.org/ticket/4046
      ), array(), array('required' => 'Your username is required.')),
      'email_address' => new sfValidatorAnd(array(
        new sfValidatorEmail(array('trim' => true), array('required' => 'Your e-mail address is required.', 'invalid' => 'The email address is invalid.')),
        new sfValidatorPropelUnique(array('trim' => true, 'model' => 'sfGuardUser', 'column' => 'email'), array('invalid' => 'This email already exists. Please choose another one.')),
      # see ticket http://trac.symfony-project.org/ticket/4046
      ), array(), array('required' => 'Your e-mail address is required.')),
      'password' => new sfValidatorString(array(), array('required' => 'Your password is required.')),
      'password_confirmation' => new sfValidatorString(array(), array('required' => 'Your password confirmation is required.')),
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_confirmation', array(), array('invalid' => 'The two passwords do not match')));

    $this->widgetSchema->setNameFormat('register[%s]');
  }
}
