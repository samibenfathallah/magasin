<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\SupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SupplierRepository;
use App\Repository\ProductRepository;

///DATATABLE
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;

class SupplierController extends AbstractController
{
    /**
     * @Route("/supplier/new", name="supplier_new")
     * @Route("/supplier/update/{id}", name="suppliet_update")
     */
    public function add(Request $request,$id=NULL,EntityManagerInterface $entityManager,SupplierRepository $supplierRepository)
    {
        $element = "Supplier";
        if($id)
        {
            $supplier=$supplierRepository->find($id);
            $typeForm = "Update";
        } else {
            $supplier = new Supplier();
            $typeForm = "Add";
        }
        
       
       
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $supplier = $form->getData();
            $entityManager->persist($supplier);
            $entityManager->flush();
    
            return $this->redirectToRoute('supplier_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'typeform' => $typeForm,
            'element' => $element
        ]);

        
    }

    /**
     * @Route("/supplier/list", name="supplier_list")
     */
    public function list(Request $request, DataTableFactory $dataTableFactory,ProductRepository $productRepository)
    {
            $resProduct=[];
            if($request->query->get('supplierProduct'))
            {
                $resProduct=$productRepository->findBy(
                ['Supplier' => $request->query->get('supplierProduct')]
                );
            }
            $element = "Supplier";
            $table = $dataTableFactory->create()
            ->add('Designation', TextColumn::class, ['label' => 'Designation', 'className' => 'bold'])
            ->add('SupplierCode', TextColumn::class, ['label' => 'Supplier Code', 'className' => 'bold'])
            ->add('Adresse', TextColumn::class, ['label' => 'Adress', 'className' => 'bold'])
            ->add('id', TextColumn::class, ['label' => 'Action', 'className' => 'bold','render' => function($value, $context) {return sprintf('<a href="update/%s">Update</a>|<a onclick="return confirm(\'Are you sure you want to delete this Supplier?\');" href="delete/%s">Delete</a>', $value, $value);}])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Supplier::class,
                
            ])
            ->handleRequest($request);
            
        if ($table->isCallback()) {
           
            return $table->getResponse();
        }

        return $this->render('list.html.twig', ['datatable' => $table,'element' => $element,'product' => $resProduct]);

        

        

        
    }


    /**
     * @Route("/supplier/delete/{id}", name="supplier_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager,SupplierRepository $supplierRepository,ProductRepository $productRepository)
    {
       
        $resProduct=$productRepository->findBy(
            ['Supplier' => $id]
        );
        
        if(!$resProduct)
        {     
             $supplier=$supplierRepository->find($id);    
             $entityManager->remove($supplier);
             $entityManager->flush();
             
        } 
            
            return $this->redirectToRoute('supplier_list',["supplierProduct"=> $id]);
       
    }
}
