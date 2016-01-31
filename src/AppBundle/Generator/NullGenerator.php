<?php
namespace AppBundle\Generator;

class NullGenerator implements IdGeneratorInterface
{
    public function generate(){
        return null;
    }
}