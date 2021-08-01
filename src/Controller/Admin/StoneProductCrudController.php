<?php

namespace App\Controller\Admin;

use App\Entity\StoneProduct;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpParser\Node\Scalar\MagicConst\File;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class StoneProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StoneProduct::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Сохранить и загрузить картинки');
            });
    }


    public function configureFields(string $pageName): iterable
    {
        $entityId = null;
        if(isset($_GET['entityId'])){
            $entityId = $_GET['entityId'];
        }

        return [
            TextField::new('name'),
            TextField::new('slug'),
            NumberField::new('weight'),
            ImageField::new('small_img')->setUploadDir('/public/images/stone/catalog')->setBasePath('images/stone/catalog/'),
            ImageField::new('big_img')->setUploadDir('/public/images/stone/big')->setBasePath('images/stone/big/'),
            AssociationField::new('parent'),
            AssociationField::new('color'),
            AssociationField::new('country'),
            TextField::new('title'),
            TextEditorField::new('description'),
            CollectionField::new('attachments')->setEntryType(AttachmentType::class)->onlyWhenUpdating(),
            DateTimeField::new('updated')->hideOnIndex(),

        ];
    }

}
