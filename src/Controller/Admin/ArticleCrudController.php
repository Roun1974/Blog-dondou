<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Form\Type\Admin\CommentType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       
            yield TextField::new('title');
            yield SlugField::new('slug')
            ->setTargetFieldName('title');
            yield AssociationField::new('categories');
            yield TextEditorField::new('content');
            yield TextField::new('featuredText', 'Texte mis en avant');
            yield DateTimeField::new('createdAt')->hideOnForm();
            yield DateTimeField::new('updatedAt')->hideOnForm();
            
       
    }
    
}
