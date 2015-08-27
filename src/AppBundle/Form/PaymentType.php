<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/07/15
 * Time: 16:14
 */

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tshirt', 'checkbox', array(
                'required' => false
            ))
            ->add('tshirtSize', 'entity', array(
                'class' => 'AppBundle\Entity\TShirt',
                'label' => 'Taille du T-shirt',
                'choice_label' => 'size',
                    'expanded' => true,

                    'required' => false,
                    'empty_value' => false,
                )
            )
            ->addEventSubscriber($this->listner);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\Payment',
        ));
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'payment';
    }
}