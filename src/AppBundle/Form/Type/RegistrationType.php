<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 05/07/2017
 * Time: 14:16
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'app.user.form.firstname'])
            ->add('lastName', TextType::class, ['label' => 'app.user.form.lastname'])
            ->add('phone', TextType::class, ['label' => 'app.user.form.phone']);
        if($options['admin']) {
            $builder
                ->add('enabled', CheckboxType::class, ['label' => 'app.user.form.enabled', 'required' => false])
                ->add('hoursByWeek', IntegerType::class, ['data' => 3, 'label' => 'app.user.form.hour']);
        }
    }

    public function getParent(): string
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix(): string
    {
        return 'app_user_registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'admin' => false
        ]);
    }


}
