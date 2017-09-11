<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Movement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                'label' => 'app.movement.form.name'
            ])
            ->add('weight', NumberType::class, [
                'label' => 'app.movement.form.weight'
            ])
            ->add('repetition', IntegerType::class, [
                'label' => 'app.movement.form.repetition'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Movement::class,
        ));
    }
}
