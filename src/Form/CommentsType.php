<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('email',EmailType::class,[
            //     'label' => 'Votre e-mail',
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            // ->add('pseudo',TextType::class,[
            //     'label' => 'Votre pseudo',
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            ->add('content', TextareaType::class,[
                'label' => 'Votre commentaire',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=> 'Ecrivez un commentaire'
                ]
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
            ->add('envoyer',SubmitType::class,[
                "attr" => [
                    "class" => "btn btn-sm btn-primary pull-right"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
