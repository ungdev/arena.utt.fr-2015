<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\Registration;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Mime_Headers_MailboxHeader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
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
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

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

            /** @var \Swift_Mime_SimpleMessage $message */
            $message = \Swift_Message::newInstance()
                ->setSubject('Inscription à l\'UTT Arena')
                ->setFrom('send@example.com')
                ->setTo($player->getEmail())
                ->setBody($this->renderView(':emails:registration.html.twig', array(
                    'login' => $player->getNickname(),
                    'firstname' => $player->getFirstName(),
                    'lastname' => $player->getLastName(),
                    'token' => $player->getToken()
                    )
                )
            , 'text/html');

            $this->get('mailer')->send($message);

            $this->addFlash('success', 'Votre inscription s\'est bien passée. Veuillez verifier votre boite de courriel pour vérifié votre compte.');
            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('error', 'Il y a des erreurs dans le formulaire');

        return $this->render('default/index.html.twig', array(
            'registration' => $form->createView()
        ));
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

        $player =$this->getDoctrine()
            ->getRepository('AppBundle:Player')
            ->findOneBy(array('token' => $token));

        if ($player) {
            $player->setEnabled(true);
            $player->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('success', 'Votre compte est maintenant activé.
            Vous pouvez maintenant vous connecter.');
        } else {
            $this->addFlash('error', 'Le token n\'est pas correct');
        }

        return $this->redirectToRoute('homepage');
    }
}
