<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/08/15
 * Time: 21:55
 */

namespace AppBundle\Form\Field;


use AppBundle\Entity\PlayerRepository;
use AppBundle\Entity\SpotlightGame;
use AppBundle\Entity\TeamSpotlightGame;
use AppBundle\Form\DataTransformer\PlayersToMembershipsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class TeammateType extends AbstractType
{

    protected $om;

    /**
     * TeammateType constructor.
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new PlayersToMembershipsTransformer($this->om, $options['team']);
        $builder->addModelTransformer($transformer, true);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('game');
        $resolver->setAllowedTypes('game', '\\AppBundle\\Entity\\TeamSpotlightGame');

        $resolver->setRequired('team');
        $resolver->setAllowedTypes('team', '\\AppBundle\\Entity\\Team');

        $resolver->setRequired('connectedPlayer');
        $resolver->addAllowedTypes('connectedPlayer', 'int');
        $resolver->addAllowedTypes('connectedPlayer', 'boolean');

        $resolver->setDefaults(array(
            'class' => 'AppBundle:Player',
            'multiple' => false,
                'required' => false,
                'empty_value' => false,
            'query_builder'=>
                function (Options $options) {
                    return function (PlayerRepository $er) use ($options) {
                        return $er->getPossibleTeammates($options['game'], $options['connectedPlayer']);
                    };
                },'connectedPlayer' => false,
            'attr' =>  array(
                        'class' => 'selectize'
                    ),
                )
        );
    }

    public function getParent()
    {
        return 'entity';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'teammate';
    }
}