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
use AppBundle\Service\Barcode;
use Doctrine\ORM\EntityRepository;
use Twig_Environment;
use Twig_Extension;

class TwigExtension extends Twig_Extension
{

    protected $gameRepository;
    protected $barcode;
    /**
     * TwigExtension constructor.
     * @param EntityRepository $gameRepository
     */
    public function __construct(EntityRepository $gameRepository, Barcode $barcode)
    {
        $this->gameRepository = $gameRepository;
        $this->barcode = $barcode;
    }


    public function getGlobals()
    {
        return array(
            'games' => $this->gameRepository->findAll()
        );
    }

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

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('barcode', function ($string, $barcodeType = 'code128'){
                return $this->barcode->dataURI($string, $barcodeType);
            })
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