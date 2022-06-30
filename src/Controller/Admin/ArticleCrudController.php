<?php

namespace App\Controller\Admin;

use App\Entity\Article;
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
            yield TextEditorField::new('content');
            yield TextareaField::new('featuredText', 'Texte mis en avant');
            yield AssociationField::new('categories');

        yield AssociationField::new('featuredImage');

        yield CollectionField::new('comments')
            ->setEntryType(CommentType::class)
            ->allowAdd(false)
            ->allowDelete(false)
            ->onlyOnForms()
            ->hideWhenCreating();
            yield DateTimeField::new('createdAt')->hideOnForm();
       
    }
    
}
