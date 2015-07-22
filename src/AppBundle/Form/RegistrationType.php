<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 18/07/15
 * Time: 21:08
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('player', new PlayerType(), array(
                'label' => false
            ))
            ->add('cgu', 'checkbox')
            ->add('rules', 'checkbox')
            ->add('submit', 'submit', array(
                'label' => 'S\'inscrire'
            ));

        $builder->get('player')->remove('submit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\Registration',
            'intention'       => 'registration',
            'cascade_validation' => true,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'registration';
    }
}