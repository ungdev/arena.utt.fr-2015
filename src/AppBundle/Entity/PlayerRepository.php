<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 27/08/15
 * Time: 02:49
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class PlayerRepository extends EntityRepository
{
    public function getPossibleTeammates(TeamSpotlightGame $game, $connectedPlayer = false) {
        $queryBuider = $this->createQueryBuilder('p')
            ->where('p.spotlightGame = ' . $game->getId())
            ->andWhere('p.id NOT IN (SELECT IDENTITY(m.player) FROM AppBundle:Membership m) ')
            ->orderBy('p.nickname', 'ASC');

        if ($connectedPlayer) {
            $queryBuider->andWhere('p.id != ' . $connectedPlayer);
        }
        return $queryBuider;
    }
}