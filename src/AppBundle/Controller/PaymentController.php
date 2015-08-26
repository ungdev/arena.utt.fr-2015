<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Model\Payment;
use AppBundle\Model\Price;
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
        $payment = new Payment();
        $form = $this->createForm('payment', $payment);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Player $player */
            $player = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $stripeToken = $request->get('stripeToken');

            $price = $this->get('pricer')->getPrice($this->getUser())->getAmount();
            $price += $payment->hasTshirt() ? $this->getParameter('price.tshirt') : 0;

            try {

                Stripe::setApiKey($this->getParameter('payment.stripe.secret'));
                Charge::create(array(
                    'source' =>  $stripeToken,
                    'amount'   => $price,
                    'currency' => 'eur'
                ));

            } catch (Card $exception) {
                $this->get('logger')->log($exception);
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
            $ticket->setReduced($this->get('pricer')->hasReducedPrice($player));
            $player->setTicket($ticket);

            $ticket->setCode($this->get('hashids')->encode($player->getId()));

            $em->persist($player);
            $em->flush();

            $this->get('sender')->send(
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
        return $this->redirectToRoute('profile');
    }

    /**
     * @return array
     *
     * @Template(":payment:form.html.twig")
     */
    public function paymentFormAction(){
        $form = $this->createForm('payment', new Payment(), array(
                'action' => $this->generateUrl('pay'),
                'method' => "POST"
            )
        );
        return array(
                'ticketPrice' => $this->get('pricer')->getPrice($this->getUser()),
                'tshirtPrice' => new Price($this->getParameter('price.tshirt')),
                'paymentForm' => $form->createView(),
                'key' => $this->getParameter('payment.stripe.publishable')
        );
    }


}