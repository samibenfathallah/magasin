<?php

namespace App\Controller;


use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints\DateTime;

///DATATABLE
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/new", name="order_new")
     * @Route("/order/update/{id}", name="order_update")
     */
    public function add(Request $request,$id=NULL,EntityManagerInterface $entityManager,OrderRepository $orderRepository,ProductRepository $productRepository)
    {
        
        $element = "Order";
        if($id)
        {
            $order=$orderRepository->find($id);
            $typeForm = "Update";
        } else {
            $order = new Order();
            $order->setQty(1);
           $order->setCustomer($this->getUser());
            
            $typeForm = "Add";
        }


        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $order = $form->getData();
            $entityManager->persist($order);
            $entityManager->flush();
            
    
            return $this->redirectToRoute('order_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'typeform' => $typeForm,
            'element' => $element
        ]);

        
    }

    /**
     * @Route("/order/list", name="order_list")
     */
    public function list(Request $request, DataTableFactory $dataTableFactory,OrderRepository $orderRepository)
    {
            $resOrder=[];
            $oneOrder=[];
            
            $res= $orderRepository->findAll();
            $product="";
            foreach ($res as $order) {
              
                $product="";
                foreach ($order->getProduct() as $p) {
                    $product .= $product=="" ? $p->getDesignation() : ",".$p->getDesignation();
                }
                $oneOrder["Product"]= $product;
                $oneOrder["Customer"]= $order->getCustomer()->getUsername();
                $oneOrder["Created At"]= $order->getCereatedAt()->format('d-m-Y');
                $resOrder[]=$oneOrder;
            }
            
            
            $element = "Order";
            $table = $dataTableFactory->create()
            ->add('Product', TextColumn::class, ['label' => 'Product', 'className' => 'bold'])
            ->add('Customer', TextColumn::class, ['label' => 'Customer', 'className' => 'bold'])
            ->add('Created At', TextColumn::class, ['label' => 'Created At', 'className' => 'bold'])
            ->createAdapter(ArrayAdapter::class, $resOrder)
            ->handleRequest($request);
            
        if ($table->isCallback()) {
           
            return $table->getResponse();
        }
        
        
        return $this->render('list.html.twig', ['datatable' => $table,'element' => $element]);

    }

    /**
     * @Route("/order/delete/{id}", name="order_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager,OrderRepository $orderRepository,ProductRepository $productRepository)
    {
       
        
       
             $category=$categoryRepository->find($id);    
             $entityManager->remove($category);
             $entityManager->flush();
            
        
            
            return $this->redirectToRoute('category_list');
       
    }
}
