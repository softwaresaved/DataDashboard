<?php

namespace AppBundle\Controller;

use AppBundle\Model\ParseData;
use AppBundle\Form\Type\CreateQueryType;
use AppBundle\Form\Type\ParseQueryType;
use AppBundle\Form\Type\GetQueryType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/data")
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login", name="_data_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="_data_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_data_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * Home listing
     * @Route("/", name="_data_home")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function adminHome()
    {
        // The user will be directed here
        return $this->render('AppBundle::default/home.html.twig');
    }

    /**
    * Function to show the create form page
    * @Route("/create")
    * @Security("is_granted('ROLE_ADMIN')")
    */
    public function createAction (Request $request)
    {
        $options = Array();
        $form = $this->createForm(new CreateQueryType(), $options);

        //handle request loop
        $form->handleRequest($request);

        if ($form->isValid()) {
             $p = new ParseQueryType();
             $p->ParseQuery($form->getData());

             return $this->redirect('../');
        }

        return $this->render('AppBundle::default/createquery.html.twig', array(
            'form' => $form->createView())
        );
    }
}
