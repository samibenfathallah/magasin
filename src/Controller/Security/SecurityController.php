<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

///DATATABLE
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;


class SecurityController extends AbstractController
{
    /**
     * @Route("/user/new", name="user_security_new")
     * @Route("/user/update/{id}", name="user_security_update")
     */
    public function add(Request $request,$id=NULL,EntityManagerInterface $entityManager,UserRepository $userRepository,UserPasswordEncoderInterface $encoder)
    {
        $element ="User";
        if($id)
        {
            $user=$userRepository->find($id);
            $typeForm = "Update";
           // $msg = 'Your changes were saved!';
            
        } else {
            $user = new User();
            $typeForm = "Add";
           // $msg = 'Category Created!';
            
        }
        
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user = $form->getData();
            //Hash Password
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);

            $entityManager->persist($user);
            $entityManager->flush();
            /*$this->addFlash(
                'notice',
                 $msg
            );*/
    
            return $this->redirectToRoute('user_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'element' => $element,
            'typeform' => $typeForm
            
        ]);
    }

    /**
     * @Route("/login", name="user_security_login")
     */
    public function login()
    {
       
        return $this->render('security/login.html.twig');

    }

    /**
     * @Route("/logout", name="user_security_logout")
     */
    public function logout()
    {
        
     }


     /**
     * @Route("/user/list", name="user_list")
     */
    public function list(Request $request, DataTableFactory $dataTableFactory,UserRepository $userRepository)
    {
           
            $element = "User";
            $table = $dataTableFactory->create()
            ->add('email', TextColumn::class, ['label' => 'Email', 'className' => 'bold'])
            ->add('username', TextColumn::class, ['label' => 'Username', 'className' => 'bold'])
            ->add('roles', TextColumn::class, ['label' => 'Roles', 'className' => 'bold','data' => function($value, $context) {return $value->getDescriptionRoles();}])
            ->add('id', TextColumn::class, ['label' => 'Action', 'className' => 'bold','render' => function($value, $context) {return sprintf('<a href="update/%s">Update</a>|<a onclick="return confirm(\'Are you sure you want to delete this User?\');" href="delete/%s">Delete</a>', $value, $value);}])
            ->createAdapter(ORMAdapter::class, [
                'entity' => User::class,
                
            ])
            ->handleRequest($request);
            
        if ($table->isCallback()) {
           
            return $table->getResponse();
        }
        
        
        return $this->render('list.html.twig', ['datatable' => $table,'element' => $element]);

    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager,UserRepository $userRepository)
    {
            
             $user=$userRepository->find($id);    
             $entityManager->remove($user);
             $entityManager->flush();
           
            return $this->redirectToRoute('user_list');
       
    }

}
