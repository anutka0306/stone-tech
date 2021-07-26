<?php


namespace App\Service;

use App\Entity\StoneCatalog;
use App\Entity\StoneProduct;
use Doctrine\ORM\EntityManagerInterface;

class UrlGeneratorService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(
        EntityManagerInterface $em
    ){
       $this->em = $em;
        $connection = $this->em->getConnection();
    }

    public function generateUrlByNewStoneCategory(StoneCatalog $stoneCatalog){
        $catalogItem = $stoneCatalog->setSlug('katalog/'.$stoneCatalog->getSlug().'/');
        $this->em->persist($catalogItem);
        $this->em->flush();
    }

    public function generateUrlByNewStoneProduct(StoneProduct $stoneProduct){
        $itemPath = $stoneProduct->setSlug($stoneProduct->getParent()->getSlug().$stoneProduct->getSlug().'/');
        $this->em->persist($itemPath);
        $this->em->flush();

    }

}