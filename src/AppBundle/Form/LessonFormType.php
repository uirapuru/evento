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
                "required" => true
            ])
            ->add("description", "textarea")
            ->add("address")
            ->add("city")
            ->add("startDate", "datetime", [
                "date_format" => "d.M.y H:i",
                "widget" => "single_text"
            ])
            ->add("endDate", "datetime", [
                "date_format" => "d.M.y H:i",
                "widget" => "single_text"
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
