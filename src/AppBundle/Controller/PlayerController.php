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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/profile/{edit}", name="profile", defaults={"edit" : ""}, requirements={"edit" : "edit|''"})
     */
    public function profileAction(Request $request, $edit = false) {

        $editForm = $this->createForm('player', $this->getUser(), array(
            'action' => $this->generateUrl('edit'),
            'method' => 'POST'
        ));

        $editForm->remove('password');

        $editForm->handleRequest($request);

        $passwordForm = $this->createFormBuilder(null, array(
            'label' => 'Mot de passe',
            'action' => $this->generateUrl('passwordChange'),
            'method' => "POST"
        ))
            ->add('password', 'passwordInput')
            ->addEventSubscriber($this->get('form.listener'))
        ->getForm();

        $passwordForm->handleRequest($request);
        $this->getDoctrine()->getManager()->refresh($this->getUser());

        return $this->render(':player:profile.html.twig', array(
                'editForm' => $editForm->createView(),
                'passwordFrom' => $passwordForm->createView(),
                'editMode' => boolval($edit),
                "payable" => $this->get('manager.place')->canPay()
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
     * @Method("POST")
     */
    public function editAction(Request $request) {
        /** @var Player $user */
        $user = $this->getUser();

        $formerEmail = $user->getEmail();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('player', $user);
        $form->remove('password');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($user);
            $em->flush();

            if ($formerEmail != $form->get('email')->getData()) {
                $this->addFlash('success', 'Vos changements ont été enregistrés.
                Puisque vous avez modifié votre email, veuillez vous reconnecter.');
                return $this->redirectToRoute('logout');
            } else {
                $em->refresh($user);
                $this->addFlash('success', 'Vos changements ont été enregistrés.');
                return $this->redirectToRoute('profile');
            }

        }

        return $this->forward('AppBundle:Player:profile', array('edit' => 'edit'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @Route("/password/change", name="passwordChange")
     * @Method("POST")
     */
    public function passwordChangeAction(Request $request) {
        $form = $this->createFormBuilder()
            ->add('password', 'passwordInput')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $player = $this->getUser();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($player, $plainPassword);

            $player->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('success', 'Votre nouveau mot de passe est maintenant opérationnel.');

            return $this->redirectToRoute('profile');
        }
        return $this->forward('AppBundle:Player:profile');
    }
}
