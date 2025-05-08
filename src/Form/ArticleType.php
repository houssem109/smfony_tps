<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'article',
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'article (5-50 caractères)'
                ]
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0.01',
                    'placeholder' => 'Entrez un prix (différent de 0)'
                ],
                'invalid_message' => 'Veuillez entrer un prix valide.'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'titre',
                'placeholder' => 'Sélectionnez une catégorie',
                'required' => true,
                'label' => 'Catégorie'
            ]);
            
        // Check if we're in edit or new context based on whether the entity has an ID
        $article = $builder->getData();
        $label = ($article && $article->getId()) ? 'Modifier' : 'Créer';
        
        $builder->add('save', SubmitType::class, [
            'label' => $label,
            'attr' => ['class' => 'btn btn-success']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'article_form',
        ]);
    }
}