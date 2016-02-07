<?php
namespace AppBundle\Command;

class CreateWorkshop implements WorkshopCommandInterface
{
    public $title;
    public $description;
    public $startDate;
    public $endDate;
    public $lessons;
    public $url;
    public $email;
    public $phone;
    public $city;
}