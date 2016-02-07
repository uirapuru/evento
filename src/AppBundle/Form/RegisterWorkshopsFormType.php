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
            ->add("title")
            ->add("slug", "text", [
                "read_only" => true,
                "mapped" => false
            ])
            ->add("description", "textarea")
            ->add("startDate", "datetime", [
                "date_format" => "d.M.y H:i",
                "widget" => "single_text"
            ])
            ->add("endDate", "datetime", [
                "date_format" => "d.M.y H:i",
                "widget" => "single_text"
            ])
            ->add(
                $builder->create("lessons", "collection", [
                    'entry_type'   => LessonFormType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false
                ])->addModelTransformer($options["lessonTransformer"])
            )
            ->add("url")
            ->add("phone")
            ->add("email")
            ->add("city", "text")
            ->add("submit", "submit", [
                "label" => "Zarejestruj"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => WorkshopCommandInterface::class,
            "cities" => [],
            "lessonTransformer" => null
        ]);
    }

    public function getName()
    {
        return 'register_workshops_form_type';
    }
}
