<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/08/15
 * Time: 21:55
 */

namespace AppBundle\Form\Field;


use AppBundle\Entity\SpotlightGame;
use AppBundle\Entity\TeamSpotlightGame;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class TeammateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMapped(false);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('game');

        $resolver->setAllowedTypes('game', '\\AppBundle\\Entity\\TeamSpotlightGame');
        $resolver->setRequired('excludedPlayers');
        $resolver->setAllowedTypes('excludedPlayers', 'array');
        $resolver->setDefaults(array(
            'class' => 'AppBundle:Player',
            'multiple' => false,
            'query_builder'=>
                function (Options $options) {
                    return function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('p')
                            ->where('p.spotlightGame = ' . $options['game']->getId())
                            ->andWhere('p.id NOT IN (' . join(',', $options['excludedPlayers']) . ')')
                            ->andWhere('p.id NOT IN (SELECT m.id FROM AppBundle:Membership m WHERE m.player = p.id) ')
                            ->orderBy('p.nickname', 'ASC');
                    };
                },
            'attr' => function (Options $options) {
                    return array(
                        'class' => 'selectize',
                        'data-maximum' => $options['game']->getTeammateNumber() - 1
                    );
                    },
            'constraints' => function (Options $options) {
                $constraints = array();
                if ($options['multiple']) {
                    $constraints[] = new Count(array(

                            'min'        => $options['game']->getTeammateNumber() - 1,
                            'max'        => $options['game']->getTeammateNumber() - 1,
                            'exactMessage' => 'Il doit y avoir exactement ' . $options['game']->getTeammateNumber() - 1 . 'coéquipiers.',
                    ));
                }
                return $constraints;
            }
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