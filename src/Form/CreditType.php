<?php


namespace App\Form;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CreditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sum',NumberType::class, [
            'label' => 'Введите сумму кредита'
        ])
            ->add('credit_term', IntegerType::class,[
                'label' => "Выберите срок кредита",
                'attr' => [
                    'min' => 1,
                    'max' => 24
                ]
            ])
            ->add('interest_rate', NumberType::class, [
                'label' => "Введите процентную ставку"
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