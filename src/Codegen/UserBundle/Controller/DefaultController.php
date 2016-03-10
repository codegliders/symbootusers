<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Codegen\UserBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller {

    public function indexAction($name) {

        return $this->render('CodegenUserBundle:Default:splash.html.twig', array('name' => $name));
    }

    public function startAction() {

        return $this->render('CodegenUserBundle:Default:startTagCloud.html.twig');
    }

    public function loginAction(Request $request) {

        $session = $request->getSession();

        $form = $this->createForm(new LoginType());

        $error = '';

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif ($session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }

        if (isset($error) && is_object($error) && method_exists($error, 'getMessage')) {
            $error = $error->getMessage();
            if ($error == 'Bad credentials') {
                $error = '';
//                // test for flashbag
//                //set messages
//                $session->getFlashBag()->add('credenziali', 'Username o password non valide. Riprova');
//
//\ì              //get messages
//                foreach ($session->getFlashBag()->get('credenziali', array()) as $message) {
//                   $error '<div role="alert" class="alert alert-warning alert-dismissible fade in">
//                  <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
//               ' . $message . '
//                  </div>';
//                }
                $error = 'Username o password errate. Riprova.';
            }
        }


        return $this->render('CodegenUserBundle:Default:loginbs.html.twig', array(
                    'loginform' => $form->createView(),
                    'error' => $error
        ));
    }

    public function splashAction() {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('CodegenUserBundle:Default:splash.html.twig');
        } else {
            return $this->render('CodegenUserBundle:Default:loginbs.html.twig');
        }
    }

    public function bssplashAction() {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('CodegenUserBundle:Default:bssplash.html.twig');
        } else {
            return $this->render('CodegenUserBundle:Default:loginbs.html.twig');
        }
    }

}
