<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Wod;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'html5' => false,
            'attr' => [
                'class' => 'datepicker inputmov form-control'
            ]
        ))
            ->add('score', TextType::class, [
            ])
            ->add('comment', TextareaType::class, [
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'app.emom' => Wod::EMOM,
                    'app.for_time' => Wod::FORTIME,
                    'app.tabata' => Wod::TABATA,
                    'app.amrap' => Wod::AMRAP,
                ],
                'required' => true
            ])
            ->add('movements', CollectionType::class, array(
                'entry_type'   => MovementType::class,
                'allow_add' => true,
                'entry_options' => array('label' => false),
                'by_reference' => false,
                'label' => false
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Wod::class,
        ));
    }
}
