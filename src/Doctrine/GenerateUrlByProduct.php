<?php


namespace App\Doctrine;

use App\Entity\Products;
use App\Service\UrlGeneratorService;

class GenerateUrlByProduct
{
    /**
     * @var UrlGeneratorService
     */
    protected $urlGeneratorService;


    public function __construct(UrlGeneratorService $urlGeneratorService){
        $this->urlGeneratorService = $urlGeneratorService;
    }

    public function postPersist(Products $products){
        $this->urlGeneratorService->generateUrlByProduct($products);
    }

}