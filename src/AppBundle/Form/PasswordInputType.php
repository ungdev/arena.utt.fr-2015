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
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordInputType extends BaseType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
                'invalid_message' => 'Les deux mot de passe ne correspondent pas.',
                'first_name' => 'password',
                'first_options' => array(
                    'attr' => array(
                        'placeholder' => 'Mot de passe',
                        'autocomplete' => 'off'
                    )
                ),
                'second_name' => 'confirmation',
                'second_options' => array(
                    'attr' => array(
                        'placeholder' => 'Confirmation',
                        'autocomplete' => 'off'
                    )
                ),
                'type' => 'password',
                'options' => array(
                    'constraints' => array(
                        new NotBlank(),
                    ),
                    'attr' => array(
                        'class' => 'password'
                    ),
                    'label' => false
                )
            )
        );
    }


    public function getParent()
    {
        return 'repeated';
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'passwordInput';
    }
}