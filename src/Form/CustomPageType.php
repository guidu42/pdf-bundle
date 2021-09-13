<?php

namespace App\Form;

use App\Entity\CustomPage;
use App\Enum\CustomPageEnum;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'entity.custom_page.title.label'
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'entity.custom_page.content.label'
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'entity.custom_page.status.label',
                'choices' => CustomPageEnum::getFormChoices(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomPage::class,
        ]);
    }
}
