<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Codegen\UserBundle\Entity\Role;
use Codegen\UserBundle\Entity\RoleRepository;
use Codegen\UserBundle\Form\RoleAddType;
use Codegen\UserBundle\Form\RoleEditType;
use Codegen\UserBundle\Form\PwdChangeType;
use Codegen\UserBundle\Form\RoleType;

class RoleController extends Controller {

    public function newAction(Request $request) {
        // creates a user providing initial data

        $task = new Role();
        //$task->setUsername('Insert user name');
        //$task->setIsActive(1);

        $form = $this->createForm(new RoleAddType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $role = $form->getData();

                $factory = $this->get('security.encoder_factory');



                $this->getDoctrine()->getManager()->persist($role);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('rolegrid'));
            }
        }

        return $this->render('CodegenUserBundle:Role:addrole.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request) {


        $id = $request->query->get('id');

        $user = $this->getDoctrine()->getRepository('CodegenUserBundle:Role')->find($id);

        $form = $this->createForm(new RoleEditType(), $user);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $role = $form->getData();
            }



            $this->getDoctrine()->getManager()->persist($user);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('rolegrid'));
        

        }

        return $this->render('CodegenUserBundle:Role:roleedit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function pwdchangeAction(Request $request) {

        $user = $this->getUser();

        $form = $this->createForm(new PwdChangeType());


        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $password = $form->get('password')->getData();

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($password, 'RANDOMSALT__RANDOMSALT');

                $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($user);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('usergrid'));
            }
        }
        return $this->render('CodegenUserBundle:User:pwdchange.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request) {


        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository('CodegenUserBundle:Role')->find($id);

        // Remove the record...
        $em->remove($user);

        $em->flush();

        return $this->redirect($this->generateUrl('rolegrid'));
    }
    
    //#0 ajax list users table data controller
    public function RoleAjaxListDataAction(Request $request) {
        $numItemsPerPage = $request->get('numberOfRecords');


        $_SESSION['numItemsPerPage'] = $numItemsPerPage;
        if ($request->get('page')) {
            $page = $request->get('page');
        } else {
            $page = 1;
        }

        $_SESSION['page'] = $page;

        $orderfield = $request->get('orderField');
        $ordersort = $request->get('orderSort');
        $searchString = $request->get('searchString');
        $searchField = $request->get('searchField');

        $orderArray = array('id' => 'DESC');
        $searchArray = array();

        if (isset($orderfield) && $orderfield !== '' && $orderfield !== null) {

            $orderArray = array($orderfield => $ordersort);
            // $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        }

        if (isset($searchString) && isset($searchField) && $searchString !== 'null' && $searchField !== 'null' && $searchString !== null && $searchField !== null) {

            // WORKING CODE DO NOT DELETE:
            $searchArray = array($searchField => $searchString);
            //PASSING A DOUBLE VALUE ARRAY DOES NOT WORK BECAUSE FINDB QUERY IS IN OR
            // $searchArray=array('firstname'=>$searchString,'lastname'=>$searchString);
            // $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        }

        $roles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);

        $allRoles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findAll();
        $numRoles = count($allRoles);



        return $this->render('CodegenUserBundle:Role:roleAjaxGridData.html.twig', array(
                    'roles' => $roles,
                    'allRoles' => $allRoles,
                    'itemsPerPage' => $numItemsPerPage,
                    'page' => $page,
                    'numUsers' => $numRoles,
        ));
    }

    public function RoleListBsAction(Request $request, $page, $id = null) {

        $task = new Role();

        $formAdd = $this->createForm(new RoleType());
        $formEdit = $this->createForm(new RoleType());


        isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 10;

        if ($request->getMethod() === "POST") {

            $formAdd->handleRequest($request);
            if ($formAdd->isValid()) {
                $role = $formAdd->getData();


                $em = $this->getDoctrine()->getManager();

                $em->persist($role);

                $em->flush();

                return $this->redirect($this->generateUrl('rolelistbs'));
            }
        }

        $roles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);


        $allRoles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findAll();
        $numRoles = count($allRoles);

        $numPages = ceil($numRoles / $numItemsPerPage);


        return $this->render('CodegenUserBundle:Role:roleListBs.html.twig', array(
                    'roles' => $roles,
                    'numRoles' => $numRoles,
                    'numPages' => $numPages,
                    'allRoles' => $allRoles,
                    'page' => $page,
                    'itemsPerPage' => $numItemsPerPage,
                    'formadd' => $formAdd->createView(),
                    'formedit' => $formAdd->createView(),
        ));
    }

    // user datagrid pager
    public function RolePagerAction(Request $request, $page) {

        $numItemsPerPage = $request->get('numberOfRecords');

        $choiceIndex = $request->get('numRecIndex');

        $_SESSION['numRecIndex'] = $choiceIndex;
        $_SESSION['numItemsPerPage'] = $numItemsPerPage;

        $page = 1;

        $orderfield = $request->get('orderField');
        $ordersort = $request->get('orderSort');
        $searchString = $request->get('searchString');
        $searchField = $request->get('searchField');

        $orderArray = array('id' => 'DESC');
        $searchArray = array();

        if (isset($orderfield) && $orderfield !== '' && $orderfield !== null) {

            $orderArray = array($orderfield => $ordersort);
            // $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        }

        if (isset($searchString) && isset($searchField) && $searchString !== 'null' && $searchField !== 'null' && $searchString !== null && $searchField !== null) {

            $searchArray = array($searchField => $searchString);

            // $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        }

        $roles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);


        $allRoles = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Role')->findBy($searchArray);

        // $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray);
        $numRoles = count($allRoles);

        $form = $this->createFormBuilder()
                ->add('numRecords', 'choice', array(
                    'choices' => array('5' => '5', '10' => '10', '20' => '20'),
                    'required' => false,
                    'empty_value' => false,
                    'preferred_choices' => array($numItemsPerPage),
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'width:80px',
                    ),
                    'data' => $choiceIndex
                        )
                )
                ->getForm();


        $numPages = ceil($numRoles / $numItemsPerPage);



        return $this->render('CodegenUserBundle:Role:rolePagerAjax.html.twig', array(
                    'roles' => $roles,
                    'allRoles' => $allRoles,
                    'numRoles' => $numRoles,
                    'page' => $page,
                    'numPages' => $numPages,
                    'itemsPerPage' => $numItemsPerPage,
                    'form' => $form->createView(),
        ));
    }

    public function roleEditAction(Request $request) {

        $id = $request->query->get('id');

        $dbUser = $this->getDoctrine()->getRepository('CodegenUserBundle:Role')->find($id);

        $form = $this->createForm(new RoleType(), $dbUser, array(
            'action' => ($this->generateUrl('roleedit') . '?id=' . $id),
        ));


        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $formRole = $form->getData();



                $this->getDoctrine()->getManager()->persist($formRole);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('rolelistbs'));
            }
        }
        
        $view = $this->render('CodegenUserBundle:Role:roleEditBs.html.twig', array(
            'formedit' => $form->createView(),
        ));

        return $view;
        
    }
    
    
    public function roleAssocUsersAction(Request $request) {

        $id = $request->query->get('id');

        $dbRole = $this->getDoctrine()->getRepository('CodegenUserBundle:Role')->find($id);
       $roleUsers=$this->getDoctrine()->getRepository('CodegenUserBundle:Role')->getAssocUsers($id);
       //$roleUsers=$dbRole->getAssocUsers($id);
        
        $view = $this->render('CodegenUserBundle:Role:roleUsersBs.html.twig', array(
           'role'=>$dbRole,
             'users'=>$roleUsers,
        ));

        return $view;
        
    }

}
