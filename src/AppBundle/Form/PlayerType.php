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
                    'placeholder' => 'Pseudo',
                    'class' => 'nickname'
                )
            ))
            ->add('password', 'passwordInput', array(
                'label' => false
            ))
            ->add('email','email', array(
                'label' => false,
                'attr' => array(
                    'minlength' => 3,
                    'maxlength' => 255,
                    'placeholder' => 'Mail',
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
                    'placeholder' => 'Nom',
                    'class' => 'last-name'
                )
            ))
            ->add('phone', 'text', array(
                'label' => false,
                'attr' => array(
                    'minlength' => 10,
                    'maxlength' => 15,
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
