<?php

namespace AppBundle\Form;

use AppBundle\DTO\SearchFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("search", "text", [
                "label" => "Szukaj",
                "required" => false,
            ])
            ->add("city", "choice", [
                "choices" => $options["cities"],
                "empty_value" => "wybierz...",
                "empty_data" => null,
                "label" => "Miasto",
                "required" => false,
            ])
            ->add("startDate", "date", [
                "widget" => "single_text",
                "label" => "Pomiędzy",
                "required" => false,
            ])
            ->add("endDate", "date", [
                "widget" => "single_text",
                "label" => "a",
                "required" => false,
            ])
            ->add("submit", "submit", [
                "label" => "Filtruj"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "cities" => [],
            "data_class" => SearchFilter::class
        ]);
    }

    public function getName()
    {
        return 'search_workshop';
    }
}
