<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label'=>'Objet :'])

            ->add('message',TextareaType::class,['label'=>'Message :'])

            ->add('recepient',EntityType::class,['class'=>User::class,'label'=>'À :','attr'=>['class'=>'selectpicker form-control']])

            ->add('Envoyer',SubmitType::class,['attr'=>['class'=>'btn btn-style btn-secondary mt-3 mb-3']]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
