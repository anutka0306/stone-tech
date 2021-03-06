<?php

namespace App\Controller\Admin;

use App\Entity\Content;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;



class ContentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Content::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Страница')
            ->setEntityLabelInPlural('Страницы')
            ->setSearchFields(['name', 'menu_name', 'path'])
            ->setPaginatorPageSize(30);
    }

    public function index(AdminContext $context)
    {
        return parent::index($context); // TODO: Change the autogenerated stub
    }


    public function configureFields(string $pageName): iterable
    {
        /*return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];*/
        return [
            Field::new('id')->onlyOnIndex(),
            Field::new('name','Заголовок'),
            Field::new('menu_name', 'Название пункта меню'),
            Field::new('menu_order', 'Позиция в меню')->hideOnIndex(),
            Field::new('path', 'URL адрес'),
            ChoiceField::new('page_type', 'Тип страницы')->setChoices([
                'Категория' => 'category',
                'Страница' => 'simple',
            ]),
            AssociationField::new('category_id', 'Категория')->hideOnIndex()->setHelp('Нужен только для типа страниц "Категория"'),
            Field::new('seo_title', 'Title')->hideOnIndex(),
            TextField::new('seo_description')->hideOnIndex(),
            CodeEditorField::new('seo_text')->setLabel('SEO текст')->setHelp('До спойлера')->hideOnIndex(),
            CodeEditorField::new('seo_text_hidden', 'Скрытый SEO текст')->setHelp('Под спойлером')->hideOnIndex(),

            ImageField::new('seo_text_img','Картинка SEO текста')->setUploadDir('/public/uploads/images')->setBasePath('/uploads/images/'),
            Field::new('parent', 'Родитель')->setHelp('ID родительской страницы'),
            Field::new('card_title', 'Заголовок карточки изделия')->hideOnIndex(),
            TextEditorField::new('card_description', 'Текст карточки изделия')->hideOnIndex(),
            ImageField::new('card_image', 'Картинка карточки')->setUploadDir('/public/uploads/images/cards_images')->setBasePath('/uploads/images/cards_images/')->hideOnIndex(),
        IntegerField::new('card_price', 'Цена в карточке')->setHelp('Рублей')->hideOnIndex(),
            /*ChoiceField::new('card_measure', 'Цена за...(единица измерения)')->setChoices([
                'изделие' => 1,
                'п.м' => 2,
            ])->hideOnIndex(),*/
            AssociationField::new('measure','Цена за...(единица измерения)')->hideOnIndex(),
            BooleanField::new('top_menu', 'Отображать в верхнем меню')->hideOnIndex(),
            BooleanField::new('index_menu', 'Отображать в меню на главной')->hideOnIndex(),
            ImageField::new('thumb_img', 'Картинка для меню на главной')->setUploadDir('/public/uploads/thumbs')->setBasePath('/uploads/thumbs/')->hideOnIndex(),
           /* DateTimeField::new('updated')->setValue(date('d.m.Y - H-i-s'))->hideOnIndex(),*/

        ];
    }

}
