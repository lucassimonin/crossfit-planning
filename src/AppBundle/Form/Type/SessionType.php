<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Product;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('day', ChoiceType::class, [
            'choices' => [
                'app.monday' => Session::MONDAY,
                'app.tuesday'  => Session::TUESDAY,
                'app.wednesday'  => Session::WEDNESDAY,
                'app.thursday'  => Session::THURSDAY,
                'app.friday'  => Session::FRIDAY,
                'app.saturday'  => Session::SATURDAY,
                'app.sunday' => Session::SUNDAY
            ],
            'required' => true
        ])
            ->add('startTime', TimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ])
            ->add('endTime', TimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ])
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
            ));
    }
}
