<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Form\Model\Registration;
use AppBundle\Form\PlayerType;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swift_Mime_Headers_MailboxHeader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class PlayerController extends Controller
{
    /**
     * Profile route
     *
     * @Route("/profile", name="profile")
     */
    public function profileAction() {
        $editForm = $this->createForm('player', $this->getUser(), array(
            'action' => $this->generateUrl('edit'),
            'method' => 'POST'
        ));
        $editForm->remove('password');

        $passwordForm = $this->createForm('passwordInput');

        return $this->render(':player:profile.html.twig', array(
                'editForm' => $editForm->createView(),
                'passwordFrom' => $passwordForm->createView()
            )
        );
    }

    /**
     * Edit a profile
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/edit", name="edit")
     */
    public function editAction(Request $request) {
        /** @var Player $user */
        $user = $this->getUser();

        $formerEmail = $user->getEmail();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('player', $user, array(

            )
        );
        $form->remove('password');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($user);
            $em->flush();

            if ($formerEmail != $form->get('email')->getData()) {

            } else {
                $this->addFlash('success', 'Vos changements ont été enregistrés.');
            }

            $em->refresh($user);
        }

        return $this->redirect('profile');
    }

}
