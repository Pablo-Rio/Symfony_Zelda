<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;;
use Symfony\Component\Validator\Constraints\NotBlank;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', null, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'https://www.youtube.com/watch?v=xxxxxxxxxxx',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une URL valide',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
