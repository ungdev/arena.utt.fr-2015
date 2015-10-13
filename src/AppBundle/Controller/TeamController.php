<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamSpotlightGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY') and user.getTicket() !== null")
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * @param Request $originalRequest
     * @return array
     * @internal param Request $request
     *
     * @Template(":team:form.html.twig")
     *
     */
    public function createFormAction(Request $originalRequest)
    {
        $form = $this->createForm('team', new Team($this->getUser()->getSpotlightGame()), array(
            'action' => $this->generateUrl('team_create'),
            'method' => "POST",
            'game' => $this->getUser()->getSpotlightGame(),
            'connectedPlayer' => $this->getUser()->getId(),
            'label' => false
        ));
        $form->handleRequest($originalRequest);
        return array(
            'form' => $form->createView(),
            'title' => 'Création de l\'équipe',
            'formId' => 'createForm'
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/create", name="team_create")
     * @Method("POST")
     * @return Response
     */
    public function createAction(Request $request)
    {
        $player = $this->getUser();
        /** @var TeamSpotlightGame $game */
        $game = $player->getSpotlightGame();
        $team = new Team($game);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('team', $team, array(
            'game' => $game,
            'connectedPlayer' => $player->getId(),
        ));

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

        return $this->forward('AppBundle:Player:profile');
    }


    /**
     * @param Request $originalRequest
     * @param Team $team
     * @return array
     * @internal param Request $request
     *
     * @Template(":team:form.html.twig")
     * @Security("user === team.getCreatedBy()")
     */
    public function editFormAction(Request $originalRequest, Team $team)
    {
        $form = $this->createForm('team', $team, array(
            'action' => $this->generateUrl('team_edit', array('name' => $team->getName())),
            'method' => "POST",
            'game' => $this->getUser()->getSpotlightGame(),
        ));

        $form->remove('memberships');

        $form->handleRequest($originalRequest);

        return array(
            'form' => $form->createView(),
            'title' => 'Edition de l\'équipe',
            'formId' => 'editForm'
        );
    }

    /**
     * @param Request $request
     *
     * @param Team $team
     * @return Response
     * @Route("/edit/{name}", name="team_edit")
     * @Method("POST")
     * @Security("user == team.getCreatedBy()")
     *
     */
    public function editAction(Request $request, Team $team)
    {
        $form = $this->createForm('team', $team, [
            'game' => $this->getUser()->getSpotlightGame()
            ]);

        $form->remove('memberships');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();


            $this->addFlash('success', 'Les changements ont été sauvegardés.');

            return $this->redirectToRoute('profile');
        }

        return $this->forward('AppBundle:Player:profile');
    }

    /**
     * @param Team $team
     * @return Response
     * @Route("/delete/{name}", name="team_delete")
     *
     * @Security("user == team.getCreatedBy()")
     *
     */
    public function deleteAction(Team $team)
    {
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
     */
    public function quitAction()
    {
        /** @var Player $player */
        $player = $this->getUser();

        /** @var Membership $membership */
        $membership = $player->getMembership();

        /** @var Team $team */
        $team = $membership->getTeam();

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

        $this->sendTeamEmail($team, "Départ d'un des membre de votre équipe", ':emails:memberLeave.html.twig');

        $this->addFlash('success', 'Vous avez quitté votre équipe.');
        return $this->redirectToRoute('profile');
    }

    /**
     * @Security("user == team.getCreatedBy()")
     *
     * @Template(":team:form.html.twig")
     *
     * @param Request $originalRequest
     * @param Team $team
     * @return array
     */
    public function addFormAction(Request $originalRequest, Team $team)
    {
        $form = $this->createFormBuilder(
            null, array(
                'action' => $this->generateUrl('team_add'),
                "method" => "POST",
                'label' => false
            )
        )->add('teammate', 'teammate', array(
            'game' => $this->getUser()->getSpotlightGame(),
            'multiple' => false,
            "team" => $team,
            'label' => 'Coéquipier',
        ))
            ->add('submit', 'submit', array(
                'label' => 'Ajouter',
                'attr' => array(
                    'class' => 'button'
                )
            ))->getForm();

        $form->handleRequest($originalRequest);

        return array(
            'form' => $form->createView(),
            'title' => 'Ajout d\'un coéquipier',
            'formId' => 'addForm'
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/add", name="team_add")
     *
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $player = $this->getUser();

        /** @var Team $team */
        $team = $player->getMembership()->getTeam();

        /** @var TeamSpotlightGame $game */
        $game = $player->getSpotlightGame();

        if ($team->getMemberships()->count() >= $game->getTeammateNumber()) {
            $this->addFlash('error', 'Impossible d\'ajouter un nouveau coéquipier.
            Il n\'y a plus de place dans l\'équipe.');
            return $this->redirectToRoute('profile');
        }

        $form = $this->createFormBuilder()->add('teammate', 'teammate', array(
            'game' => $game,
            'connectedPlayer' => false,
            'multiple' => false
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
            $em->persist(new Membership($team, $player));
            $em->flush();

            $this->addFlash('success', $newMember->getNickname() . ' est maintenant dans votre équipe.');
            return $this->redirectToRoute('profile');
        }

        return $this->forward('AppBundle:Player:profile');
    }

    protected function sendTeamEmail(Team $team, $subject, $template) {
        foreach ($team->getMemberships() as $member){
            /** @var Player $player */
            $player = $member->getPlayer();
            $this->get('app.mail')->send($player->getEmail(), $subject, $this->renderView(
                $template,
                array(
                'team' => $team,
                'player' => $player
            )));
        }
    }
}
