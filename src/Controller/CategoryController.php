<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

///DATATABLE
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/new", name="category_new")
     * @Route("/category/update/{id}", name="category_update")
     */
    public function add(Request $request,$id=NULL,EntityManagerInterface $entityManager,CategoryRepository $categoryRepository)
    {
        
        $element = "Category";
        if($id)
        {
            $category=$categoryRepository->find($id);
            $typeForm = "Update";
        } else {
            $category = new Category();
            $typeForm = "Add";
        }


        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
            
    
            return $this->redirectToRoute('category_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'typeform' => $typeForm,
            'element' => $element
        ]);

        
    }

    /**
     * @Route("/category/list", name="category_list")
     */
    public function list(Request $request, DataTableFactory $dataTableFactory,ProductRepository $productRepository)
    {
            $resProduct=[];
            if($request->query->get('categoryProduct'))
            {
                $resProduct=$productRepository->findBy(
                ['Category' => $request->query->get('categoryProduct')]
                );
            }
            $element = "Category";
            $table = $dataTableFactory->create()
            ->add('Designation', TextColumn::class, ['label' => 'Designation', 'className' => 'bold'])
            ->add('Description', TextColumn::class, ['label' => 'Description', 'className' => 'bold'])
            ->add('id', TextColumn::class, ['label' => 'Action', 'className' => 'bold','render' => function($value, $context) {return sprintf('<a href="update/%s">Update</a>|<a onclick="return confirm(\'Are you sure you want to delete this Category?\');" href="delete/%s">Delete</a>', $value, $value);}])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Category::class,
                
            ])
            ->handleRequest($request);
            
        if ($table->isCallback()) {
           
            return $table->getResponse();
        }
        
        
        return $this->render('list.html.twig', ['datatable' => $table,'element' => $element,'product' => $resProduct]);

    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager,CategoryRepository $categoryRepository,ProductRepository $productRepository)
    {
       
        $resProduct=$productRepository->findBy(
            ['Category' => $id]
        );
        
        if(!$resProduct)
        {     
             $category=$categoryRepository->find($id);    
             $entityManager->remove($category);
             $entityManager->flush();
            
        } 
            
            return $this->redirectToRoute('category_list',["categoryProduct"=> $id]);
       
    }
}
