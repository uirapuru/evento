<?php

namespace AppBundle\Form;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\CreateWorkshop;
use AppBundle\Command\WorkshopCommandInterface;
use AppBundle\Entity\Lesson;
use AppBundle\Form\Transformer\LessonToCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterWorkshopsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text", [
                "label" => "form.workshop_title"
            ])
            ->add("description", "textarea", [
                "label" => "form.description"
            ])
            ->add("lessons", "collection", [
                'entry_type'   => LessonFormType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                "label" => false,
            ])
            ->add("url", "url", [
                "label" => "form.url"
            ])
            ->add("phone", "text", [
                "label" => "form.phone"
            ])
            ->add("email", "email", [
                "label" => "form.email"
            ])
            ->add("submit", "submit", [
                "label" => "form.register",
                "attr" => [
                    "class" => "btn btn-success pull-right"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => WorkshopCommandInterface::class,
            "cities" => [],
        ]);
    }

    public function getName()
    {
        return 'register_workshops_form_type';
    }
}
