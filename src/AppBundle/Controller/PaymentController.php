<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Model\Payment;
use AppBundle\Model\Price;
use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class PaymentController extends Controller {

    /**
     * @param Request $request
     *
     * @Route("/pay", name="pay")
     * @Method("POST")
     *
     * @return Response
     */
    public function payAction(Request $request){

        if (!$this->get('manager.place')->canPay()) {
            $this->addFlash('error', 'Désolé mais les inscription sont closes');
            $this->redirectToRoute('profile');
        }

        $payment = new Payment();
        $form = $this->createForm('payment', $payment);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Player $player */
            $player = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $stripeToken = $request->get('stripeToken');

            $price = $this->get('manager.price')->getPrice($this->getUser())->getAmount();
            $price += $payment->hasTshirt() ? $this->getParameter('price.tshirt') : 0;

            try {
                Stripe::setApiKey($this->getParameter('payment.stripe.secret'));
                Charge::create(array(
                    'source' =>  $stripeToken,
                    'amount'   => $price,
                    'currency' => 'eur'
                ));

            } catch (Card $exception) {
                $this->get('logger')->log(Logger::ERROR, $exception->getMessage());
                $this->addFlash('error', 'Une erreur de paiement est survenue.
                Nous vous invitons à contacter l\' équipe organisatrice de l\'évenement');
                return $this->redirectToRoute('profile');
            }

            /** @var Ticket $ticket */
            $ticket = new Ticket();

            $ticket->setMethod('stripe');

            if ($payment->hasTshirt()) {
                $tshirt = $em->find('AppBundle:TShirt', $payment->getTshirtSize());
                $ticket->setTshirt($tshirt);
            }
            $ticket->setPrice($price);
            $ticket->setReduced($this->get('manager.price')->hasReducedPrice($player));
            $player->setTicket($ticket);

            $ticket->setCode($this->get('hashids')->encode($player->getId()));

            $em->persist($player);
            $em->flush();

            $this->get('manager.mail')->send(
                $player->getEmail(),
                'Paiement de la place pour la LAN',
                $this->render(
                    ':emails:payment.html.twig',
                    array(
                        'player' => $player
                    )
                )
            );
        }
        return $this->forward('AppBundle:Player:profile');
    }

    /**
     * @return array
     *
     * @Template(":payment:form.html.twig")
     */
    public function paymentFormAction(Request $originalRequest){
        $form = $this->createForm('payment', new Payment(), array(
                'action' => $this->generateUrl('pay'),
                'method' => "POST"
            )
        );

        $form->handleRequest($originalRequest);
        return array(
                'ticketPrice' => $this->get('manager.price')->getPrice($this->getUser()),
                'tshirtPrice' => new Price($this->getParameter('price.tshirt')),
                'paymentForm' => $form->createView(),
                'key' => $this->getParameter('payment.stripe.publishable'),
                'payable' => $this->get('manager.place')->canPay()
        );
    }


}