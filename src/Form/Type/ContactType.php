<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        

        $builder
        ->add('firstname', TextType::class, [
            'constraints' => new NotBlank(),
        ])
        ->add('lastname', TextType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('pnumber', TelType::class, [
            'required'   => false
        ])
        ->add('note', TextareaType::class, [
            'required'   => false,
        ])
        ->add('submit', SubmitType::class);



    }
}