<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 01/08/15
 * Time: 22:32
 */

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TokenType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', 'text', [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Pseudo'
                ),
                'constraints' => array(
                new NotBlank(),
                new Length(['min' => 3])),
            ])
            ->add('oldEmail', 'email', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Mail entré à l\'inscription'
                ),
                'constraints' => array(
                    new Email(),
                    new NotBlank(),
                    new Length(['min' => 3]),
                )
                )
            )
            ->add('newEmail', 'email', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nouveau mail'
                ),
                'constraints' => array(
                    new Email()
                )
            ))->addEventSubscriber($this->listner);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'intention'       => 'resendToken',
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
        return 'token';
    }
}
