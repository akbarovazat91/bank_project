<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('full_name',TextType::class, [
            'label' => 'Введите ФИО клиента',
            'attr' => [
                'placeholder' => 'ФИО'
            ]
        ])
            ->add('status', CheckboxType::class,[
                'label' => "Статус",
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'attr' => ['var'=> false]
        );
    }
    public function getBlockPrefix()
    {
        return 'app_bundle_object_type';
    }

}