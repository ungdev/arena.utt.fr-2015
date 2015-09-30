<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\Registration;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\TokenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    /**
     * Registration route
     *
     * @Route ("/register", name="register")
     * @Method ("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if (!$this->get('app.place')->canRegister()) {
            $this->addFlash('error', 'Désolé mais les inscription sont closes');
            $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('registration', new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Registration $registration */
            $registration = $form->getData();

            $player = $registration->getPlayer();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($player, $player->getPassword());

            $player->setPassword($encoded);

            $player->setToken(uniqid());

            $em->persist($player);
            $em->flush();


            $this->get('app.mail')->send(
                $player->getEmail(),
                'Inscription à l\'UTT Arena',
                $this->renderView(
                    ':emails:registration.html.twig',
                    array (
                        'login' => $player->getNickname(),
                        'firstname' => $player->getFirstName(),
                        'lastname' => $player->getLastName(),
                        'token' => $player->getToken()
                    )
                )
            );

            $this->addFlash('success', 'Votre inscription a été correctement prise en compte! Veuillez vérifier votre boite mail afin de la valider.');
            return $this->redirectToRoute('homepage');
        }

        return $this->forward('AppBundle:Default:index');
    }

    /**
     * Confirm email
     *
     * @param string $token confirmation token
     *
     * @return Response
     *
     * @Route("/confirm/{token}", name="confirm")
     */
    public function confirmAction($token) {

        $player = $this->getDoctrine()
            ->getRepository('AppBundle:Player')
            ->findOneBy(array('token' => $token, 'enabled' => false));

        if ($player) {
            $player->setEnabled(true);
            $player->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('success', 'Votre compte est maintenant activé.
            Vous pouvez maintenant vous connecter.');
        } else {
            $this->addFlash('error', 'Le jeton de confirmation n\'est pas correct');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Resend token
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/token/resend", name="resendToken")
     */
    public function resendToken(Request $request) {

        $form = $this->createForm('token', null, array(
            'method' => 'POST',
            'label' => false
        ));

        $form->handleRequest($request);

        $data = $form->getData();

        if ($form->isValid()) {
            $player =$this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneBy(array(
                    'email' => $data['oldEmail'],
                    'nickname' => $data['nickname'],
                    'enabled' => false
                ));

            if ($player) {
                $player->setToken(uniqid());

                if (isset($data['newEmail'])) {
                    $player->setEmail($data['newEmail']);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();

                $this->get('app.mail')->send(
                    $player->getEmail(),
                    'Renvoi du jeton de confirmation',
                    $this->renderView(':emails:resendToken.html.twig', array(
                            'login' => $player->getNickname(),
                            'token' => $player->getToken(),
                            'firstName' => $player->getFirstName(),
                            'lastName' => $player->getLastName()
                        )
                    )
                );

                $this->addFlash('success', 'Un jeton de confirmation vous a été renvoyé.
                Veuillez vérifier votre boîte de courriel.');

                return $this->redirectToRoute('homepage');
            }
            else {
                $this->addFlash('error', "Aucun joueur ne pouvant demander un renvoi de
                jeton de confirmation n'a été trouvé.");
            }
        }

        return $this->render(':registration:resendToken.html.twig', ['form' => $form->createView()]);
    }
}
