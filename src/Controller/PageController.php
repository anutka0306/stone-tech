<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Repository\ProductsRepository;


class PageController extends AbstractController
{
    /**
     * @var ContentRepository
     */
   protected $page_repository;
   protected $products_repository;

   public function __construct(ContentRepository $repository, ProductsRepository $productsRepository)
   {
       $this->page_repository = $repository;
       $this->products_repository = $productsRepository;
   }

    /**
     * @Route("/{token}", name="dynamic_pages",requirements={"token"= ".+\/$"})
     */

    public function index($token){
        if(!$page = $this->products_repository->findOneBy(['path'=>$token]) AND !$page = $this->page_repository->findOneBy(['path'=>$token]) ){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }

        if($page instanceof Products){
            return $this->product($page);
        }

        if($page instanceof Content) {
            if ($page->getPageType() == 'category') {
                return $this->category($page);
            }
            //потом над этим подумать
            if ($page->getPageType() == 'simple') {
                return $this->simple($page);
            }
        }
    }
    
    

    private function simple($simple){
        return $this->render('page/simple.html.twig',[
           'page' => $simple
        ]);
    }

    private function category($category){
        $ourWorks = $this->getOurWorkImages($category->getPath());
        $products = $this->getProducts($this->products_repository, $category->getCategoryId());
        $colors = $this->getColors($products);
        return $this->render('page/category.html.twig',[
           'category'=>$category,
            'works' => $ourWorks,
            'products' => $products,
            'colors' => $colors,
        ]);
    }

    private function product($product){
        return $this->render('product/index.html.twig',[
            'product'=>$product,
        ]);
    }

    private function getOurWorkImages($folderName){
        $finder = new Finder();
        $folder = trim(str_replace('/',' ', $folderName));
        $folder = str_replace(' ','-', $folder);
        $filesystem = new Filesystem();
        if($filesystem->exists($_SERVER['DOCUMENT_ROOT'].'/images/our_works/'.$folder)){
            $finder->files()->name(['*.jpeg','*.jpg','*.png'])->in($_SERVER['DOCUMENT_ROOT'].'/images/our_works/'.$folder);
            $files = array();
            foreach ($finder as $file){
                $files[] = '/images/our_works/'.$folder.'/'.$file->getFilename();
            }
        }else{
            $files = null;
        }


        return $files;
    }

    private function getProducts(ProductsRepository $productsRepository, $categoryId){
        $products = $productsRepository->findBy(['category_id' => $categoryId]);
        return $products;
    }

    private function getColors($products){
        $colors = array();
        foreach ($products as $product){
            $colors[] = $product->getColor();
        }
        return array_unique($colors);

    }

}
