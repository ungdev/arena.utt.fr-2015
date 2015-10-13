<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginFormAction() {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(':security:loginForm.html.twig', array(
                'error' => $error,
                'lastUsername' => $lastUsername
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {}

    /**
     * @param Request $request
     *
     * @Route("/password/request", name="requestPassword")
     *
     * @return Response
     */
    public function passwordRequestAction(Request $request){
        $form = $this->createForm('passwordRequest', null, array(
                'label' => false
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            /** @var Player $player */
            $player = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Player')
                ->findOneBy(
                    array(
                        'email' => $data['email'],
                        'nickname' => $data['nickname'],
                        'enabled' => true
                    )
                );

            if ($player) {
                $token = uniqid();

                $player->setToken($token);

                $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();

                $this->get('app.mail')->send($data['email'], 'Reinitialisation du mot de passe',
                    $this->renderView(':emails:requestPasswordReset.html.twig', array(
                            'token' => $token,
                            'player' => $player
                        )
                    )
                );

                $this->addFlash('success', 'Un mail va bientôt vous parvenir.
            Veuillez suivre ses instructions.');

                return $this->redirectToRoute('homepage');

            } else {
                $this->addFlash('error', "Aucun joueur ne pouvant demander une
                réinitialisation du mot de passe n'a été trouvé.");
            }
        }
        return $this->render(":security:password.html.twig", array(
            'form' => $form->createView(),
             'title'  =>  "Demande d'un nouveau mot de passe."
            )
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/password/reset/{token}", name="resetPassword")
     *
     * @return Response
     */
    public function passwordResetAction(Request $request, $token){
        $form = $this->createForm('passwordInput', null, array(
                'label' => false
            )
        );

        /** @var Player $player */
        $player = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Player')
            ->findOneBy(array('token' => $token, 'enabled' => true));

        if (!$player) {
            $this->addFlash('error', "Ce jeton est invalide.");
            return $this->redirectToRoute("homepage");
        }

        if ($player->getUpdatedAt()->diff(new \DateTime(), true)->days > 1) {
            $this->addFlash('error', "Ce jeton a expiré.");
            return $this->redirectToRoute("homepage");
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $plainPassword = $form->getData();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($player, $plainPassword);

            $player->setPassword($encoded);
            $player->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('success', 'Votre nouveau mot de passe est maintenant opérationnel.
            Vous pouvez vous connecter.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(":security:password.html.twig", array(
                'form' => $form->createView(),
                'title' => "Réinitialisation du mot de passe"
            )
        );
    }
}
