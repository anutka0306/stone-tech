<?php

namespace App\Controller\Admin;

use App\Entity\CityPages;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CityPagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CityPages::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Страница по городам')
            ->setEntityLabelInPlural('Страницы по городам')
            ->setSearchFields(['name', 'city', 'path'])
            ->setPaginatorPageSize('100');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id')->onlyOnIndex(),
            Field::new('name'),
            Field::new('path'),
            AssociationField::new('parent')->setRequired(true),
            AssociationField::new('city')->setRequired(true),
            Field::new('seo_title')->hideOnIndex(),
            TextEditorField::new('seo_description')->hideOnIndex(),
            TextEditorField::new('seo_text')->hideOnIndex(),
            TextEditorField::new('seo_text_hidden')->hideOnIndex(),
            ImageField::new('seo_text_img','Картинка SEO текста')->setUploadDir('/public/uploads/images')->setBasePath('/uploads/images/'),
        ];
    }

}
