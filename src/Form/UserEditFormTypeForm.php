<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Veuillez entrer votre nom.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Veuillez entrer votre email.']),
                    new \Symfony\Component\Validator\Constraints\Email(['message' => 'L\'email n\'est pas valide.']),
                ],
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+?[1-9]\d{1,14}$/',
                        'message' => 'Numéro de téléphone invalide.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                        'allowEmptyString' => true,
                    ]),
                ],
                'help' => 'Laissez vide pour ne pas modifier le mot de passe.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
