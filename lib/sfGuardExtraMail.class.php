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
 * @author     Gordon Franke <gfranke@savedcite.com>
 * @version    SVN: $Id$
 */
class sfGuardExtraMail
{
  /**
   * send mail
   *
   * @param array $params some params for the mail
   */
  static public function send(array $params)
  {
    if(!(isset($params['module']) and isset($params['action']) and isset($params['to']) and isset($params['subject']) and isset($params['message'])))
    {
      throw new sfException('You must provide the following parameter to, subject and message');
    }
  	mail($params['to'], $params['subject'], $params['message']);
  }
}
