<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamSpotlightGame;
use AppBundle\Security\Voter\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY') and user.getTicket() !== null")
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/create", name="team_create")
     * @Template(":team:form.html.twig")
     * @return Response
     */
    public function createAction(Request $request)
    {
        $player = $this->getUser();
        /** @var TeamSpotlightGame $game */
        $game = $player->getSpotlightGame();
        $team = new Team($game);

        $this->denyAccessUnlessGranted(TeamVoter::CREATE, $team);


        $form = $this->get('form.factory')->createNamedBuilder('create', 'team', $team, array(
            'action' => $this->generateUrl('team_create'),
            'method' => "POST",
            'game' => $this->getUser()->getSpotlightGame(),
            'connectedPlayer' => $this->getUser()->getId(),
            'label' => false
        ))->getForm();


        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $team->addMembership(new Membership($team, $player));
            $team->setCreatedBy($player);
            $em->persist($team);
            $em->flush();

            $this->sendTeamEmail($team,'Création de l\' équipe', ':emails:teamCreation.html.twig');

            $this->addFlash('success', 'Votre équipe est créée.');
            return $this->redirectToRoute('profile');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('error', "La création de l'équipe a échouée");
            return $this->redirectToRoute('profile');
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Création de l\'équipe',
            'formId' => 'createForm'
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/edit/", name="team_edit")
     * @Template(":team:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $player = $this->getUser();
        /** @var Team $team */
        $team = $player->getMembership()->getTeam();

        $this->denyAccessUnlessGranted(TeamVoter::EDIT, $team);

        $form = $this->get('form.factory')->createNamedBuilder('edit', 'team', $team, array(
            'action' => $this->generateUrl('team_edit'),
            "method" => "POST",
            'game' => $player->getSpotlightGame(),
            'label' => false,
        ))->getForm();

        $form->remove('memberships');

        /** @var Form $form */
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();


            $this->addFlash('success', 'Les changements ont été sauvegardés.');

            return $this->redirectToRoute('profile');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('error', "L'éditon de l'équipe a échouée");
            return $this->redirectToRoute('profile');
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Edition de l\'équipe',
            'formId' => 'editForm'
        );
    }

    /**
     * @Template(":team:form.html.twig")
     * @param Request $request
     * @return array
     *
     * @Route("/add", name="team_add")
     */
    public function addAction(Request $request)
    {
        $player = $this->getUser();

        /** @var Team $team */
        $team = $player->getMembership()->getTeam();

        $this->denyAccessUnlessGranted(TeamVoter::ADD, $team);

        /** @var TeamSpotlightGame $game */
        $game = $player->getSpotlightGame();

        if ($team->getMemberships()->count() >= $game->getTeammateNumber()) {
            $this->addFlash('error', 'Impossible d\'ajouter un nouveau coéquipier.
            Il n\'y a plus de place dans l\'équipe.');
            return $this->redirectToRoute('profile');
        }

        $form = $this->get('form.factory')->createNamedBuilder('add', 'form', null, array(
            'action' => $this->generateUrl('team_add'),
            'method' => "POST",
            'label' => false
        ))->add('teammate', 'teammate', array(
            'game' => $this->getUser()->getSpotlightGame(),
            'multiple' => false,
            "team" => $team,
            'label' => 'Coéquipier',
        ))->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $newMember = $form->get('teammate')->getData();
            if ($newMember->getMembership()) {
                $this->addFlash('error', 'Impossible d\'ajouter le nouveau coéquipier. ' . $newMember->getNickname() .
                    ' est déjà dans une équipe.');
                return $this->redirectToRoute('profile');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist(new Membership($team, $newMember));
            $em->flush();

            $this->addFlash('success', $newMember->getNickname() . ' est maintenant dans votre équipe.');
            return $this->redirectToRoute('profile');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('error', "L'ajout du coéquipier à échouée");
            return $this->redirectToRoute('profile');
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Ajout d\'un coéquipier',
            'formId' => 'addForm'
        );
    }


    /**
     * @return Response
     * @Route("/delete", name="team_delete")
     */
    public function deleteAction()
    {
        $team = $this->getUser()->getMembership()->getTeam();

        $this->denyAccessUnlessGranted(TeamVoter::DELETE, $team);

        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        $this->sendTeamEmail($team, 'Dissolution de l\'équipe', ':emails:teamDeletion.html.twig');

        $this->addFlash('success', 'Votre équipe est bien supprimée. Un mail a été envoyé aux membres restants de votre ancienne équipe.');

        return $this->redirectToRoute('profile');
    }

    /**
     * @return Response
     *
     * @Route("/quit", name="team_quit")
     *
     * @Security("user.getMembership() != null")
     */
    public function quitAction()
    {
        /** @var Player $player */
        $player = $this->getUser();

        /** @var Membership $membership */
        $membership = $player->getMembership();

        /** @var Team $team */
        $team = $membership->getTeam();

        $this->denyAccessUnlessGranted(TeamVoter::QUIT, $team);

        $team->removeMembership($membership);

        if ($team->getMemberships()->count() == 0) {
            return $this->forward('AppBundle:Team:delete', array('team' => $team));
        }

        if ($team->getCreatedBy() == $player) {
            $team->setCreatedBy($team->getMemberships()->first()->getPlayer());
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($membership);
        $em->persist($team);
        $em->flush();

        $this->sendTeamEmail(
            $team,
            "Départ d'un des membre de votre équipe",
            ':emails:memberLeave.html.twig',
            array('leavingMember' => $membership->getPlayer())
        );

        $this->addFlash('success', 'Vous avez quitté votre équipe.');
        return $this->redirectToRoute('profile');
    }

    protected function sendTeamEmail(Team $team, $subject, $template, $params = array()) {
        foreach ($team->getMemberships() as $member){
            /** @var Player $player */
            $player = $member->getPlayer();
            $this->get('app.mail')->send($player->getEmail(), $subject, $this->renderView(
                $template,
                array_merge(
                        array(
                            'team' => $team,
                            'player' => $player
                        ),
                        $params
                    )
                )
            );
        }
    }
}
