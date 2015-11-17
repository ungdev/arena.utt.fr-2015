<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/07/15
 * Time: 16:14
 */

namespace AppBundle\Form;

use AppBundle\Entity\TShirtRepository;
use AppBundle\EventListner\FormListener;
use AppBundle\Service\Place;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends BaseType
{
    /** @var  TShirtRepository */
    protected $tshirtRepository;

    /** @var  Place */
    protected $place;

    public function __construct(FormListener $listener, EntityRepository $repository, Place $place) {
        parent::__construct($listener);
        $this->tshirtRepository = $repository;
        $this->place = $place;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->place->canTshirt()) {
            $builder
                ->add('tshirt', 'checkbox', array(
                        'required' => false
                    )
                )
                ->add('tshirtSize', 'choice', array(
                        'choices' => $this->tshirtRepository->getSizes(),
                        'label' => 'Taille du tee-shirt',
                        'expanded' => true,
                        'required' => false,
                        'empty_value' => false,
                        'multiple' => false
                    )
                )
                ->add('tshirtGender', 'choice', array(
                        'choices' => $this->tshirtRepository->getGenders(),
                        'label' => 'Coupe du tee-shirt',
                        'expanded' => true,
                        'required' => false,
                        'empty_value' => false,
                        'multiple' => false,
                        'choice_label' => function ($allChoices, $currentChoiceKey) {
                            switch ($currentChoiceKey) {
                                case 'M' :
                                    return '♂ Homme' ;
                                case 'F' :
                                    return '♀ Femme' ;
                            }
                            return 'unisex';
                        },
                    )
                );
        }
        $builder->addEventSubscriber($this->listener);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'AppBundle\Form\Model\Payment',
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
        return 'payment';
    }
}
