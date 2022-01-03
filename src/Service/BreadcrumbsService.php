<?php


namespace App\Service;
use App\Dto\BreadcrumbsItemDTO;
use App\Entity\CityPages;
use App\Entity\Content;
use App\Entity\Contracts\PageInterface;
use App\Entity\Products;
use App\Entity\StoneCatalog;
use App\Entity\StoneProduct;
use App\Repository\ContentRepository;

class BreadcrumbsService
{
    /**
     * @var ContentRepository
     */
    protected $content_repository;

    public function __construct(ContentRepository $content_repository)
    {
        $this->content_repository = $content_repository;
    }

    /**
     * @param PageInterface $page
     * @param string|null $current_name
     *
     */
    public function getItems(Content $page, string $current_name = null): array
    {
        $arr = array();
        $items = $this->getBreadChain($page->getParent(), $arr);
        return array(array_reverse(array_merge(array($page), $items)));

    }

    public function getCityPageItems(CityPages $page, string $current_name = null):array
    {
        $arr = array();
        $items = $this->getBreadChain($page->getParent()->getId(), $arr);
        return array(array_reverse(array_merge(array($page), $items)));
    }

    public function getProductItems(Products $page, string $current_name = null): array
    {
        $arr = array();
        $category = $page->getCategoryId()->getId();
        $items = $this->getBreadChain($this->content_repository->findOneBy(['category_id' => $category])->getId(), $arr);
        return array(array_reverse(array_merge(array($page), $items)));

    }

   public function getStoneCatalogItems(StoneCatalog $page, string $current_name = null):array
   {

       $items = array($this->content_repository->findOneBy(['page_type' =>'stonecatalog']), $this->content_repository->find(1));
       return array(array_reverse(array_merge(array($page), $items)));
   }

    public function getStoneProductItems(StoneProduct $page, string $current_name = null):array
    {
        $parent = $page->getParent();
        $items = array($parent, $this->content_repository->findOneBy(['page_type' =>'stonecatalog']), $this->content_repository->find(1));
        return array(array_reverse(array_merge(array($page), $items)));
    }

    private function getBreadChain(int $parent, array $chain):array{

        if($parent != null) {
            $item = $this->content_repository->find($parent);
            $chain[] = $item;
            if($item->getParent() !== null){
                return $this->getBreadChain($item->getParent(), $chain);
        }

        }else{
            $chain[] = $this->content_repository->find(1);
            return $chain;
        }
        return $chain;

    }

    private function getChainRecursive(Content $page): array
    {
        $chain = [];
        $item  = new BreadcrumbsItemDTO($page->getName(), $page->getPath());
        if ($page instanceof Content && $page->getId() === 1) {
            $item->name = 'Главная';
        }
        $chain[] = $item;
        $parent  = $this->content_repository->find($page->getParent());
        if (null === $parent) {
            return $chain;
        }
        if (is_string($parent)) {
            $parent = $this->content_repository->findOneBy(['path'=>$parent]);
        }

        return array_merge($this->getChainRecursive($parent), $chain);
    }

}