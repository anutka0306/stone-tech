<?php


namespace App\Doctrine;
use App\Entity\StoneCatalog;
use App\Service\UrlGeneratorService;


class GenerateUrlByNewStoneCategory
{
    /**
     * @var UrlGeneratorService
     */
    protected $urlGeneratorService;

    public function __construct(UrlGeneratorService $urlGeneratorService){
        $this->urlGeneratorService = $urlGeneratorService;
    }

    public function postPersist(StoneCatalog $stoneCatalog){
        $this->urlGeneratorService->generateUrlByNewStoneCategory($stoneCatalog);
    }
}