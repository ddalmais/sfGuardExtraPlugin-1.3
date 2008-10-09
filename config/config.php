<?php

if (sfConfig::get('app_sf_guard_extra_plugin_routes_register', true) && in_array('sfGuardForgotPassword', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect('routing.load_configuration', array('sfGuardExtraRouting', 'listenToRoutingLoadConfigurationEvent'));
}
