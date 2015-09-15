<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Form\Model\Registration;
use AppBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $registration = new Registration();
        $form = $this->createForm('registration', $registration, array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        return $this->render('default/index.html.twig', array(
            'registration' => $form->createView(),
            'registrable' => $this->get('app.place')->canRegister()
        ));
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction()
    {
        return $this->render('default/privacy.html.twig');
    }

    /**
     * @Route("/terms", name="terms")
     */
    public function termsAction()
    {
        return $this->render('default/terms.html.twig');
    }
}
