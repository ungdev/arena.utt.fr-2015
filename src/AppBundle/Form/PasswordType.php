<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 01/08/15
 * Time: 22:31
 */

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'repeated', array(
                'first_name' => 'password',
                'first_options' => array(
                    'label' => 'Mot de passe',
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
            ))->addEventSubscriber($this->listner);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'intention' => 'resetPassword',
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'password';
    }
}