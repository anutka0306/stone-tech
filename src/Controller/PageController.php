<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Products;
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

   public function __construct(ContentRepository $repository, ProductsRepository $productsRepository, PaginatorInterface $paginator, ColorRepository $color_repository)
   {
       $this->page_repository = $repository;
       $this->products_repository = $productsRepository;
       $this->paginator = $paginator;
       $this->color_repository = $color_repository;
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
        if(!$page = $this->products_repository->findOneBy(['path'=>$token]) AND !$page = $this->page_repository->findOneBy(['path'=>$token]) ){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }

        if($page instanceof Products){
            return $this->product($page);
        }

        if($page instanceof Content) {
            if ($page->getPageType() == 'category') {
                return $this->category($page, $paginator, $request);
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
