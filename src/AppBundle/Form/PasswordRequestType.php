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

class PasswordRequestType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                    'label' => 'Adresse mail'
                )
            )
            ->add('nickname', 'text', array(
                    'label' => 'Pseudo'
                )
            )
            ->addEventSubscriber($this->listener);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'intention' => 'passwordRequest',
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
        return 'passwordRequest';
    }
}
