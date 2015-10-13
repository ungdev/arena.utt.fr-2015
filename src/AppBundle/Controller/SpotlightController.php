<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/08/15
 * Time: 13:32
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Player;
use AppBundle\Entity\SpotlightGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SpotlightController
 * @package AppBundle\Controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY') and user.getTicket() !== null")
 * @Route("/spotlight")
 */
class SpotlightController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/select/{game}", name="select")
     * @ParamConverter("game", class="AppBundle:SpotlightGame", options={"mapping": {"game": "slug"}})
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function selectAction(Request $request, SpotlightGame $game){
       /** @var Player $player */
        $player = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($player->getSpotlightGame()) {
            $this->addFlash('success', 'Vous ne pouvez pas changer de jeu Spotlight.');
            return $this->redirectToRoute('profile');
        }

        if (!$game->isSelectable()) {
            $this->addFlash('success', "Il n'y a plus de place disponible pour ce jeu.");
            return $this->redirectToRoute('profile');
        }

        $player->setSpotlightGame($game);

        $em->persist($player);
        $em->flush();
        $em->refresh($player);

        $this->addFlash('success', 'Votre jeu Spotlight a bien été choisi.');

        return $this->redirectToRoute('profile');
    }
}
