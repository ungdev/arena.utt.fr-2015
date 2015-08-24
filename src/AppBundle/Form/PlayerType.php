<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 18/07/15
 * Time: 22:05
 */

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', 'text', array(
                'label' => false,
                'attr' => array(
                    'minlength' => 2,
                    'maxlength' => 255,
                    'placeholder' => 'Pseudonyme',
                    'class' => 'nickname'
                )
            ))
            ->add('password', 'repeated', array(
                'first_name' => 'password',
                'first_options' => array(
                    'attr' => array(
                        'placeholder' => 'Mot de passe'
                    )
                ),
                'second_name' => 'confirmation',
                'second_options' => array(
                    'attr' => array(
                        'placeholder' => 'Confirmation'
                    )
                ),
                'type' => 'password',
                'options' => array(
                    'attr' => array(
                        'class' => 'password'
                    ),
                    'label' => false
                )
            ))
            ->add('email','email', array(
                'label' => false,
                'attr' => array(
                    'minlength' => 3,
                    'maxlength' => 255,
                    'placeholder' => 'Courriel',
                    'class' => 'email'
                )
            ))
            ->add('firstName', 'text', array(
                'label' => false,
                'attr' => array(
                    'maxlength' => 255,
                    'placeholder' => 'Prénom',
                    'class' => 'first-name'
                )
            ))
            ->add('lastName', 'text', array(
                'label' => false,
                'attr' => array(
                    'maxlength' => 255,
                    'placeholder' => 'Nom de famille',
                    'class' => 'last-name'
                )
            ))
            ->add('phone', 'text', array(
                'label' => false,
                'attr' => array(
                    'minlength' => 10,
                    'maxlength' => 10,
                    'placeholder' => 'Téléphone',
                    'class' => 'phone'
                )
            ))->addEventSubscriber($this->listner);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Player',
            'intention'       => 'player',
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'player';
    }
}