<?php 
namespace App\Form;

use App\Entity\PriceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minPrice', NumberType::class, [
                'label' => 'Prix minimum',
                'required' => false,
                'attr' => ['step' => '0.01'],
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'Prix maximum',
                'required' => false,
                'attr' => ['step' => '0.01'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PriceSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}