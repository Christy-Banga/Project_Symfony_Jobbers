<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,['label'=>'Email:'])
            ->add('name',TextType::class,['label'=>'Nom:'])
            ->add('firstname',TextType::class,['label'=>'Prénoms:'])
            ->add('ville',TextType::class,['label'=>'Ville:'])
            ->add('commune',TextType::class,['label'=>'Commune:'])
            ->add('Telephone',TextType::class,['label'=>'Numéro de téléphone:'])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class ,
                [
                    "type"=>PasswordType::class,
                    "mapped"=>false,
                    "first_options"=>[
                        "label"=>"Mot de passe:",
                        "attr" => [
                            'class' => 'form-control mb-3',
                        ]
                    ],
                    "second_options"=>[
                        "label"=>"Confirmez votre mot de passe:",
                        "attr" => [
                            'class' => 'form-control mb-3',
                        ]
                    ],
                    'invalid_message'=>'la confirmation n \' est pas similaire au mot de passe.',
                    'constraints'=>[new NotBlank(['message' =>'enter le mot de passe',]),
                        new Length(["min"=>6,'minMessage'=>'Votre mot de passe doit être au moins {{ limit }} caractères',
                            'max'=>4096])  ]
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
