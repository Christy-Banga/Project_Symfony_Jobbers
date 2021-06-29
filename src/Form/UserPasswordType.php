<?php


namespace App\Form;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


/**
 * Class UserPasswordType
 * @package App\Form
 */
class UserPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword',RepeatedType::class,
                ["type"=>PasswordType::class,
                    "mapped"=>false,
                    "first_options"=>["label"=>"mot de passe:"],
                    "second_options"=>["label"=>"Confirmez votre mot de passe:"],
                    'invalid_message'=>'la confirmation n \' est pas similaire au mot de passe.',
                    'constraints'=>[new NotBlank(),
                        new Length(["min"=>8])      ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefault("data_class",User::class);
    }

}