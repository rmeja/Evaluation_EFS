<?php
/**
 * Created by PhpStorm.
 * User: remy
 * Date: 26/03/15
 * Time: 10:01
 */

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class CasAuthenticationToken extends AbstractToken {

  /**
   * Returns the user credentials.
   *
   * @return mixed The user credentials
   */
  public function getCredentials()
  {
    // TODO: Implement getCredentials() method.
  }
}