<?php


namespace App\Doctrine;
use App\Entity\StoneCatalog;
use App\Entity\StoneProduct;
use App\Service\UrlGeneratorService;


class GenerateUrlByNewStoneProduct
{
    /**
     * @var UrlGeneratorService
     */
    protected $urlGeneratorService;


    public function __construct(UrlGeneratorService $urlGeneratorService){
        $this->urlGeneratorService = $urlGeneratorService;
    }

    public function postPersist(StoneProduct $stoneProduct){
        $this->urlGeneratorService->generateUrlByNewStoneProduct($stoneProduct);
    }
}