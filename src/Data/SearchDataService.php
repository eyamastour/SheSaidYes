<?php

namespace App\Data;

class SearchDataService
{

    /**
     * @var int 
     */
    public $page = 1;
    /**
     * @var string
     */
    public $searchValue = '';

    /**
     * @var array
     */
    public  $categories = [];


    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */

    public $promo = false;
}
