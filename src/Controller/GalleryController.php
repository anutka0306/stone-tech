<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class GalleryController extends AbstractController
{
    /**
     * @var ContentRepository
     */
    protected $page_repository;

    protected $sliders_folder;

    public function __construct(ContentRepository $page_repository)
    {
        $this->page_repository = $page_repository;
        $this->sliders_folder = array(
            'stupeni' => 'Ступени',
            'podokonniki' => 'Подоконники',
            'kamini' => 'Камины',
            'stoleshnicy' => 'Столешницы',
            'other' => 'Прочие изделия',
        );
    }

    /**
     * @Route("/galereja", name="gallery")
     */
    public function index(): Response
    {
        if(!$page = $this->page_repository->findOneBy(['path'=>'galereja/'])){
            throw $this->createNotFoundException(sprintf('Page %s not found', 'galereja/'));
        }
        return $this->render('gallery/index.html.twig', [
            'page' => $page,
            'galleries' => $this->getGalleries($this->sliders_folder)
        ]);
    }

    private function getGalleries($folderList){
        $files = array();
        foreach ($folderList as $folderName => $h1){
            $filesystem = new Filesystem();
            $finder = new Finder();
            $files[$h1] = array();
            if($filesystem->exists($_SERVER['DOCUMENT_ROOT']. '/uploads/gallery/'.$folderName)){
                $finder->files()->name(['*.jpeg','*.jpg','*.png'])->in($_SERVER['DOCUMENT_ROOT'].'/uploads/gallery/'.$folderName);
                foreach ($finder as $file){
                    $files[$h1][] = '/uploads/gallery/'.$folderName.'/'.$file->getFilename();
                }
            }
        }
        return $files;

       /* $folder = trim(str_replace('/',' ', $folderName));
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


        return $files;*/
    }
}
