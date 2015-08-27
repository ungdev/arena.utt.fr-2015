<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class TeamType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Nom'
                )
            )
            ->add('memberships', 'teammate', array(
                'game' => $options['game'],
                'label' => 'Coéquipiers',
                'connectedPlayer' => $options['connectedPlayer'],
                'multiple' => true,
                'team' => $builder->getData(),
                'constraints' => array(new Count(array(
                        'min' => $options['game']->getTeammateNumber() - 1,
                        'max' => $options['game']->getTeammateNumber() - 1,
                        'exactMessage' => 'Il doit y avoir exactement ' . ($options['game']->getTeammateNumber() - 1) . ' coéquipiers.',
                    )
                    )
                    ),
                    'attr' => array(
                        'class' => 'selectize',
                        'data-maximum' => $options['game']->getTeammateNumber() - 1
                    )
                ))
            ->addEventSubscriber($this->listner);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Team',
            'intention' => 'team',
            "connectedPlayer" => false,
            'label' => false
        ));

        $resolver->setDefined('game');
        $resolver->setDefined('connectedPlayer');
    }

    public function getName()
    {
        return 'team';
    }
}
