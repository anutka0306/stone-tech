<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class PageController extends AbstractController
{
    /**
     * @var ContentRepository
     */
   protected $page_repository;

   public function __construct(ContentRepository $repository)
   {
       $this->page_repository = $repository;
   }

    /**
     * @Route("/{token}", name="dynamic_pages",requirements={"token"= ".+\/$"})
     */

    public function index($token){
        if(!$page = $this->page_repository->findOneBy(['path'=>$token])){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }
        if($page->getPageType() == 'category'){
            return $this->category($page);
        }
        //потом над этим подумать
        if($page->getPageType() == 'simple'){
            return $this->category($page);
        }
    }

    private function category($category){
        $ourWorks = $this->getOurWorkImages($category->getPath());
        return $this->render('page/category.html.twig',[
           'category'=>$category,
            'works' => $ourWorks,
        ]);
    }

    private function getOurWorkImages($folderName){
        $finder = new Finder();
        $folder = trim(str_replace('/','', $folderName));
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

}
