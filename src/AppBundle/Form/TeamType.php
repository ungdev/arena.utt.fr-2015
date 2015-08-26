<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'Nom'
        ))
            ->add('teammate', 'teammate', array(
                'game' => $options['game'],
                'mapped' => false,
                'label' => 'Membre de l\'équipe',
                'excludedPlayers' => $options['excludedPlayers'],
                'multiple' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Team',
            'intention'       => 'team',
            "excludedPlayers" => array(''),
        ));

        $resolver->setDefined('game');
        $resolver->setDefined('excludedPlayers');
    }

    public function getName()
    {
        return 'team';
    }
}
