<?php
/**
 * Created by PhpStorm.
 * User: remy
 * Date: 26/03/15
 * Time: 10:10
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use App\Security\CasAuthenticationToken;


class CasAuthentificationListener implements ListenerInterface {

   /**
   * This interface must be implemented by firewall listeners.
   *
   * @param GetResponseEvent $event
   */
  public function handle(GetResponseEvent $event) {
    // TODO: Implement handle() method.
  }

}