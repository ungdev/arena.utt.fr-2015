<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/08/15
 * Time: 18:02
 */

namespace AppBundle\Twig;

use AppBundle\Entity\SpotlightGame;
use AppBundle\Entity\TeamSpotlightGame;
use Twig_Extension;

class TwigExtension extends Twig_Extension
{


    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('teamSpotlightGame', function (SpotlightGame $game) {
                return $game instanceof TeamSpotlightGame;
            }),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig.extension';
    }
}