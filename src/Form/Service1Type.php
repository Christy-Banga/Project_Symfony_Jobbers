<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Service1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label'=>'Titre du service:']) 
            ->add('content',TextType::class,['label'=>'Se presenter:'])
            ->add('category',EntityType::class,['class'=>Category::class,
                'label'=>'Métiers:',
                'expanded'=>false,
                'multiple'=>false
            ])
            ->add('images',FileType::class,
                ['label'=>'Parcourir:',
                    'multiple'=>true,
                    'mapped'=>false,
                    'required'=>false]
            )
            ->add('Valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
