<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Product;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'Name', 'required' => true])
            ->add('price', MoneyType::class, ['label' => 'Price', 'required' => true])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => true])
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
            ));
    }
}
