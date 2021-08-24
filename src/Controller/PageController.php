<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Products;
use App\Entity\StoneCatalog;
use App\Entity\StoneProduct;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\Paginator;
use App\Repository\ColorRepository;
use App\Repository\StoneCatalogRepository;
use App\Repository\StoneProductRepository;



class PageController extends AbstractController
{
    /**
     * @var ContentRepository
     */
   protected $page_repository;
    /**
     * @var PaginatorInterface
     */
    protected $paginator;
    /**
     * @var ProductsRepository
     */
   protected $products_repository;
    /**
     * @var ColorRepository
     */
   protected $color_repository;

    /**
     * @var StoneCatalogRepository
     */
    protected $stoneCatalogRepository;

    /**
     * @var StoneProductRepository
     */
    protected $stoneProductRepository;

   public function __construct(ContentRepository $repository, ProductsRepository $productsRepository, PaginatorInterface $paginator, ColorRepository $color_repository, StoneCatalogRepository $stoneCatalogRepository, StoneProductRepository $stoneProductRepository)
   {
       $this->page_repository = $repository;
       $this->products_repository = $productsRepository;
       $this->paginator = $paginator;
       $this->color_repository = $color_repository;
       $this->stoneCatalogRepository = $stoneCatalogRepository;
       $this->stoneProductRepository = $stoneProductRepository;
   }

    /**
     * @Route("/{token}color/{color_token}", name="dynamic_catalog", requirements={"token"= ".+\/$", "color_token"=".+"})
     */
    public function catalog_color($token, $color_token,  PaginatorInterface $paginator, Request  $request, EntityManagerInterface $em):Response{
        if(!$page = $this->products_repository->findOneBy(['path'=>$token]) AND !$page = $this->page_repository->findOneBy(['path'=>$token]) ){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }
        if(!$color = $this->color_repository->findOneBy(['slug' => $color_token]) ){
            throw $this->createNotFoundException(sprintf('Color %s not found', $color_token));
        }
        if($page instanceof Content) {
            if ($page->getPageType() == 'category') {
                return $this->category_color($page, $color->getId(), $paginator, $request);
            }
        }

    }

    /**
     * @Route("/{token}", name="dynamic_pages",requirements={"token"= ".+\/$"})
     */

    public function index($token, PaginatorInterface $paginator, Request $request, EntityManagerInterface $em){
        if(!$page = $this->products_repository->findOneBy(['path'=>$token]) AND !$page = $this->page_repository->findOneBy(['path'=>$token]) AND !$page = $this->stoneCatalogRepository->findOneBy(['slug'=>$token]) AND !$page = $this->stoneProductRepository->findOneBy(['slug'=>$token])){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }

        if($page instanceof Products){
            return $this->product($page);
        }
        if($page instanceof StoneCatalog){
            $items = $this->stoneProductRepository->findBy(['parent' => $page->getId()]);
            return $this->render('stone_catalog/list.html.twig',
                [
                    'page' => $page,
                    'items' => $items,
                    ]
            );
        }

        if($page instanceof StoneProduct){
            $product = $this->stoneProductRepository->findOneBy(['slug' => $token]);
            return $this->render('stone_catalog/item.html.twig',
                [
                    'page' => $page,
                    'product' => $product,
                ]
            );
        }

        if($page instanceof Content) {
            if ($page->getPageType() == 'category') {
                return $this->category($page, $paginator, $request);
            }
            //потом над этим подумать
            if ($page->getPageType() == 'simple') {
                return $this->simple($page);
            }

            //каталог камня
            if($page->getPageType() == 'stonecatalog'){
                return $this->stoneCatalog($page);

            }
        }

    }


    private function stoneCatalog($page){
        $categories = $this->stoneCatalogRepository->findAll();
        foreach ($categories as $category){
            $images_thumb = $this->stoneProductRepository->findBy(['parent' => $category->getId()],[],4);
            $category->thumbs = $images_thumb;
        }
        return $this->render('stone_catalog/index.html.twig', [
                    'page' => $page,
                    'categories' => $categories,
                ]);
    }

    private function simple($simple){
        return $this->render('page/simple.html.twig',[
           'page' => $simple
        ]);
    }

    private function category($category, $paginator, $request){
        $sort = null;
        if (isset($_POST['ajax']) && isset($_POST['sort'])){
            $sort = $_POST['sort'];
        }

        $ourWorks = $this->getOurWorkImages($category->getPath());
        $products = $this->getProducts($this->products_repository, $category->getCategoryId(), $sort);
        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            16 /*limit per page*/
        );
        $pagination->setParam('_fragment', 'catalog-anchor');
        $colors = $this->getColors($products);
        $min_price = $this->products_repository->findOneBy(['category_id' =>$category->getCategoryId()], ['price'=>'ASC']);

        if(isset($_POST['ajax'])){
            return $this->render('ajax/catalog.html.twig',[
                'path'=>$category->getPath(),
                'category' =>$category->getCategoryId(),
                'products' => $products,
                'colors' => $colors,
                'pagination'=>$pagination,
                'activeColor' => null,
            ]);
        }

        return $this->render('page/category.html.twig',[
           'category'=>$category,
            'works' => $ourWorks,
            'products' => $products,
            'colors' => $colors,
            'pagination'=>$pagination,
            'activeColor' => null,
            'minPrice' => $min_price,
        ]);
    }

    private function category_color($category, $color, $paginator, $request){
        $sort = null;
        if (isset($_POST['ajax']) && isset($_POST['sort'])){
            $sort = $_POST['sort'];
        }

        $ourWorks = $this->getOurWorkImages($category->getPath());
        $products = $this->getProductsByColor($this->products_repository, $this->color_repository, $category->getCategoryId(), $color, $sort);
        $all_category_products = $this->getProducts($this->products_repository, $category->getCategoryId(), $sort);
        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            16 /*limit per page*/
        );
        $pagination->setParam('_fragment', 'catalog-anchor');
        $colors = $this->getColors($all_category_products);
        $colorName = $this->color_repository->find($color);
        if(isset($_POST['ajax'])){
            return $this->render('ajax/catalog.html.twig',[
                'path'=>$category->getPath(),
                'category' =>$category->getCategoryId(),
                'products' => $products,
                'colors' => $colors,
                'pagination'=>$pagination,
                'activeColor' => null,
            ]);
        }

        return $this->render('page/category_color.html.twig',[
            'category'=>$category,
            'works' => $ourWorks,
            'products' => $products,
            'colors' => $colors,
            'pagination'=>$pagination,
            'activeColor' => $color,
            'colorName' => $colorName->getColorPlural(),
            'colorPath' => $colorName->getSlug(),
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

    private function getProducts(ProductsRepository $productsRepository, $categoryId, $sort){
        if(!isset($sort)){
            $products = $productsRepository->findBy(['category_id' => $categoryId]);
        }else{
            $products = $productsRepository->findBy(['category_id' => $categoryId], ['price'=>$sort]);
        }
        return $products;
    }

    private function getProductsByColor(ProductsRepository $productsRepository, ColorRepository $colorRepository, $categoryId, $color, $sort){
        if(!isset($sort)){
            $products = $productsRepository->findBy([
                'category_id' => $categoryId,
                'color' => $color,
            ]);
        }else {
            $products = $productsRepository->findBy([
                'category_id' => $categoryId,
                'color' => $color,
            ], ['price' => $sort]);
        }
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
