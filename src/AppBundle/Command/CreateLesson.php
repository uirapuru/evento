<?php
namespace AppBundle\Command;

class CreateLesson implements LessonCommandInterface
{
    public $title;
    public $description;
    public $startDate;
    public $endDate;
    public $address;
    public $city;
    public $workshop;
}