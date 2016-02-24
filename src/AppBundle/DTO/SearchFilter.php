<?php
namespace AppBundle\DTO;

/**
 * Class SearchFilter
 * @package AppBundle\DTO
 */
class SearchFilter
{
    /** @var string */
    public $city;

    /** @var  string */
    public $search;

    /** @var \DateTime */
    public $startDate;

    /** @var \DateTime */
    public $endDate;

    /**
     * @return bool
     */
    public function isEmpty(){
        return is_null($this->city) && is_null($this->search) && is_null($this->startDate) && is_null($this->endDate);
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5(serialize(get_object_vars($this)));
    }
}