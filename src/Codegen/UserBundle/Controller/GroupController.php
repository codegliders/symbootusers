<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Codegen\UserBundle\Entity\Group;
use Codegen\UserBundle\Form\GroupEditType;
use Codegen\UserBundle\Form\GroupType;

class GroupController extends Controller {

    public function newAction(Request $request) {
        // creates a user providing initial data

        $task = new Group();
        //$task->setUsername('Insert user name');
        //$task->setIsActive(1);

        $form = $this->createForm(new GroupEditType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $role = $form->getData();

                $factory = $this->get('security.encoder_factory');



                $this->getDoctrine()->getManager()->persist($role);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('groupgrid'));
            }
        }

        return $this->render('CodegenUserBundle:Group:groupedit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request) {


        $id = $request->query->get('id');

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find($id);

        $form = $this->createForm(new GroupEditType(), $group);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $group = $form->getData();
            }



            $this->getDoctrine()->getManager()->persist($group);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('rolegrid'));
        }

        $view = $this->render('CodegenUserBundle:Group:groupedit.html.twig', array(
            'form' => $form->createView(),
        ));

        return $view;
    }

    public function deleteAction(Request $request) {


        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find($id);

        //check fk errors:
        //get group users;
        $assocUsers = $group->getUsers();
        if (count($assocUsers) > 0) {
            
           error_log("fk error tra gruppi e utenti");
            $session = $request->getSession();
            $session->getFlashBag()->add('groupfkerror', 'Questo gruppo non può essere eliminato perché è associato ad uno o più utenti. Fai click su visualizza per l\'elenco degli utenti associati');
            
        } else {
            // Remove the record...
            $em->remove($group);

            $em->flush();
            
        return $this->redirect($this->generateUrl('groupgrid'));
        }

         return $this->redirect($this->generateUrl('grouplistbs'));

    }

    //#0 ajax list users table data controller
    public function GroupAjaxListDataAction(Request $request) {
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

        $groups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);

        $allGroups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findAll();
        $numGroups = count($allGroups);



        return $this->render('CodegenUserBundle:Group:groupAjaxGridData.html.twig', array(
                    'groups' => $groups,
                    'allGroups' => $allGroups,
                    'itemsPerPage' => $numItemsPerPage,
                    'page' => $page,
                    'numUsers' => $numGroups,
        ));
    }

    public function GroupListBsAction(Request $request, $page, $id = null) {

        $task = new Group();

        $formAdd = $this->createForm(new GroupType());
        $formEdit = $this->createForm(new GroupType());


        isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 10;

        if ($request->getMethod() === "POST") {

            $formAdd->handleRequest($request);
            if ($formAdd->isValid()) {
                $group = $formAdd->getData();


                $em = $this->getDoctrine()->getManager();

                $em->persist($group);

                $em->flush();

                return $this->redirect($this->generateUrl('grouplistbs'));
            }
        }

        $groups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);


        $allGroups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findAll();
        $numGroups = count($allGroups);

        $numPages = ceil($numGroups / $numItemsPerPage);


        return $this->render('CodegenUserBundle:Group:groupListBs.html.twig', array(
                    'groups' => $groups,
                    'numGroups' => $numGroups,
                    'numPages' => $numPages,
                    'allGroups' => $allGroups,
                    'page' => $page,
                    'itemsPerPage' => $numItemsPerPage,
                    'formadd' => $formAdd->createView(),
                    'formedit' => $formAdd->createView(),
        ));
    }

    // user datagrid pager
    public function GroupPagerAction(Request $request, $page) {

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

        $groups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);


        $allGroups = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:Group')->findBy($searchArray);

        // $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray);
        $numGroups = count($allGroups);

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


        $numPages = ceil($numGroups / $numItemsPerPage);



        return $this->render('CodegenUserBundle:Group:groupPagerAjax.html.twig', array(
                    'groups' => $groups,
                    'allGroups' => $allGroups,
                    'numGroups' => $numGroups,
                    'page' => $page,
                    'numPages' => $numPages,
                    'itemsPerPage' => $numItemsPerPage,
                    'form' => $form->createView(),
        ));
    }

    public function groupEditAction(Request $request) {

        $id = $request->query->get('id');

        $dbUser = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find($id);

        $form = $this->createForm(new GroupType(), $dbUser, array(
            'action' => ($this->generateUrl('groupedit') . '?id=' . $id),
        ));


        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $formGroup = $form->getData();



                $this->getDoctrine()->getManager()->persist($formGroup);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('grouplistbs'));
            }
        }

        $view = $this->render('CodegenUserBundle:Group:groupEditBs.html.twig', array(
            'formedit' => $form->createView(),
        ));

        return $view;
    }

    public function groupAssocUsersAction(Request $request) {

        $id = $request->query->get('id');

        $dbGroup = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find($id);
        $groupUsers = $dbGroup->getUsers();

        $view = $this->render('CodegenUserBundle:Group:groupUsersBs.html.twig', array(
            'group' => $dbGroup,
            'users' => $groupUsers,
        ));

        return $view;
    }

}
