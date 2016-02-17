<?php

namespace AppBundle\Form;

use AppBundle\Command\CreateLesson;
use AppBundle\Entity\Lesson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text", [
                "required" => true,
                "label" => "form.title",
            ])
            ->add("description", "textarea", [
                "label" => "form.description",
            ])
            ->add("address", "text", [
                "label" => "form.address",
            ])
            ->add("city", "text", [
                "label" => "form.city",
            ])
            ->add("startDate", "datetime", [
                "format" => "yyyy-MM-dd HH:mm",
                "widget" => "single_text",
                "label" => "form.startDate",
                "attr" => [
                    "autocomplete" => "off"
                ]
            ])
            ->add("endDate", "datetime", [
                "format" => "yyyy-MM-dd HH:mm",
                "widget" => "single_text",
                "label" => "form.endDate",
                "attr" => [
                    "autocomplete" => "off"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => CreateLesson::class,
        ]);
    }

    public function getName()
    {
        return 'lesson_form_type';
    }
}
