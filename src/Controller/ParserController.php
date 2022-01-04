<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CityPagesRepository;
use Doctrine\ORM\EntityManagerInterface;

class ParserController extends AbstractController
{
    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $em;


    /**
     * @var CityPagesRepository
     */
    protected $cityPagesRepository;



    public function __construct(ContentRepository $contentRepository, CategoryRepository $categoryRepository, CityRepository $cityRepository, EntityManagerInterface $em, CityPagesRepository $cityPagesRepository){
        $this->contentRepository = $contentRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cityRepository = $cityRepository;
        $this->em = $em;
        $this->categories = array(1,2,3,6,7,8,21,22,23);
        $this->cityPagesRepository = $cityPagesRepository;
        $this->title = array(
            1 => 'Столешницы из натурального камня купить $CityChange$ | Цена на изготовление столешниц из камня $City$ ',
            2 => 'Столешницы из мрамора купить $CityChange$ | Цена на изготовление мраморных столешниц $City$',
            3 => 'Столешницы из гранита купить $CityChange$ | Цена на изготовление гранитных столешниц $City$',
            6 => 'Ступени и лестницы из натурального камня купить $CityChange$ | Цена на изготовление ступеней и лестниц из камня $City$',
            7 => 'Ступени и лестницы из мрамора купить $CityChange$ | Цена на изготовление мраморных ступеней и лестниц $City$',
            8 => 'Ступени и лестницы из гранита купить $CityChange$ | Цена на изготовление гранитных ступеней и лестницы $City$',
            21 => 'Подоконники из натурального камня купить $CityChange$ | Цена на изготовление подоконников из камня $City$',
            22 => 'Подоконники из мрамора купить $CityChange$ | Цена на изготовление мраморных подоконников $City$',
            23 => 'Подоконники из гранита купить $CityChange$ | Цена на изготовление гранитных подоконников $City$'
        );
        $this->description = array(
            1 => 'Столешницы из натурального камня купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка столешниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать столешницы из камня по низким ценам $City$ ☎ 8(495)961-26-90.',
            2 => 'Столешницы из мрамора купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка столешниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать мраморные столешницы по низким ценам $City$ ☎ 8(495)961-26-90.',
            3 => 'Столешницы из гранита купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка столешниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать гранитные столешницы по низким ценам $City$ ☎ 8(495)961-26-90.',
            6 => 'Ступени и лестницы из натурального камня купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка ступеней и лестниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать ступени и лестницы из камня по низким ценам $City$ ☎ 8(495)961-26-90.',
            7 => 'Ступени и лестницы из мрамора купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка ступеней и лестниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать мраморные ступени и лестницы по низким ценам $City$ ☎ 8(495)961-26-90.',
            8 => 'Ступени и лестницы из гранита купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка ступеней и лестниц за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать гранитные ступени и лестницы по низким ценам $City$ ☎ 8(495)961-26-90.',
            21 => 'Подоконники из натурального камня купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка подоконников за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать подоконники из камня по низким ценам $City$ ☎ 8(495)961-26-90.',
            22 => 'Подоконники из мрамора купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка подоконников за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать мраморные подоконники по низким ценам $City$ ☎ 8(495)961-26-90.',
            23 => 'Подоконники из гранита купить $CityChange$. ⭐ Бесплатный выезд и замер. ✅ Изготовление и установка подоконников за 4-7 дней. ✅ Гарантия 2 года. ⭐ Заказать гранитные подоконники по низким ценам $City$ ☎ 8(495)961-26-90.'
        );

    }

    /**
     * @Route("/parser", name="parser")
     */

    public function index(): Response
    {
        $cities = $this->cityRepository->findAll();
        foreach ($cities as $city){
            foreach ($this->categories as $category) {
                $category_page = $this->contentRepository->findOneBy(['category_id'=>$category]);
                $cur_path = $category_page->getPath().$city->getAlias()."/";
                var_dump($cur_path);
                //exit();
                if(is_null($this->cityPagesRepository->findOneBy(['path' => $cur_path]))) {
                    $stmt = $this->em->getConnection();
                    $title = str_replace(array('$City$', '$CityChange$'), array($city->getName(), $city->getChangeName()),$this->title[$category]);
                    $description = str_replace(array('$City$', '$CityChange$'), array($city->getName(), $city->getChangeName()),$this->description[$category]);
                    $stmt->executeQuery("INSERT INTO `city_pages` (`parent_id`, `city_id`, `name`, `path`, `seo_title`, `seo_description`) VALUES ({$category_page->getId()}, {$city->getId()}, '{$category_page->getName()} {$city->getChangeName()}', '{$category_page->getPath()}{$city->getAlias()}/','{$title}','{$description}')");
                }
            }
        }


        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController',
        ]);
    }
}
