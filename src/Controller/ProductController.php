<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
///DATATABLE
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;

class ProductController extends AbstractController
{
     /**
     * @Route("/product/new", name="product_new")
     * @Route("/product/update/{id}", name="product_update")
     */
    public function add(Request $request,$id=NULL,EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $element = "Product";
        if($id)
        {
            $product=$productRepository->find($id);
            
            $typeForm = "Update";
        } else {
            $product = new Product();
            $typeForm = "Add";
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();
            
            return $this->redirectToRoute('product_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'typeform' => $typeForm,
            'element' => $element
        ]);

        
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    public function list(Request $request, DataTableFactory $dataTableFactory)
    {
        
            $element = "Product";
            $table = $dataTableFactory->create()
                            ->add('Designation', TextColumn::class, ['label' => 'Designation', 'className' => 'bold'])
                            ->add('Description', TextColumn::class, ['label' => 'Description', 'className' => 'bold'])
                            ->add('Category', TextColumn::class, ['field' => 'Category.Designation','label' => 'Category', 'className' => 'bold'])
                            ->add('Supplier', TextColumn::class, ['field' => 'Supplier.Designation','label' => 'Supplier', 'className' => 'bold'])
                            ->add('Qty', TextColumn::class, ['label' => 'Qty', 'className' => 'bold'])
                            ->add('price', TextColumn::class, ['label' => 'Price', 'className' => 'bold'])
                            ->add('CreatAt', DateTimeColumn::class, ['format' => 'd-m-Y','label' => 'Creat At', 'className' => 'bold'])
                            ->add('id', TextColumn::class, ['label' => 'Action', 'className' => 'bold','render' => function($value, $context) {return sprintf('<a href="update/%s">Update</a>|<a onclick="return confirm(\'Are you sure you want to delete this Product?\');" href="delete/%s">Delete</a>', $value, $value);}])
                            ->createAdapter(ORMAdapter::class, [
                                            'entity' => Product::class,
                                             ])
                             ->handleRequest($request);
                             
            
        if ($table->isCallback()) {
           
            return $table->getResponse();
        }

        return $this->render('list.html.twig', ['datatable' => $table,'element' => $element]);
        
    }


    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager,ProductRepository $productRepository)
    {
       
        $product=$productRepository->find($id);    
        $entityManager->remove($product);
        $entityManager->flush();
            
            return $this->redirectToRoute('product_list');
       
    }
}
