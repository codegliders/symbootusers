<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Component\Form\FormEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Codegen\UserBundle\Entity\User;
use Codegen\UserBundle\Form\UserType;
use Codegen\UserBundle\Form\UserEditBsType;
use Codegen\UserBundle\Form\UserEditType;
use Codegen\UserBundle\Form\UserEdit2Type;
use Codegen\UserBundle\Form\UserPwdChangeType;
//use Codegen\UserBundle\Form\PwdChangeBsType;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller {

    public function newAction(Request $request) {
        // creates a user providing initial data

        $task = new User();
        //$task->setUsername('Insert user name');
        //$task->setIsActive(1);

        $form = $this->createForm(new UserType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $form->getData();

                foreach ($user->getGroups() as $group) {
                    $group->addUser($user);
                    $this->getDoctrine()->getManager()->persist($group);
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');

                $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($user);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('usergrid'));
            }
        }

        return $this->render('CodegenUserBundle:User:adduser.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function newbsAction(Request $request) {
        // creates a user providing initial data

        $task = new User();
        //$task->setUsername('Insert user name');
        //$task->setIsActive(1);

        $form = $this->createForm(new UserType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $form->getData();



                foreach ($user->getGroups() as $group) {
                    $group->addUser($user);
                    $this->getDoctrine()->getManager()->persist($group);
                }

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');
                $user->setPassword($password);
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('usergrid'));
            }
        }

        return $this->render('CodegenUserBundle:User:adduserbs.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request) {


        $id = $request->query->get('id');

        $dbUser = $this->getDoctrine()->getRepository('CodegenUserBundle:User')->find($id);

        $form = $this->createForm(new UserEditType(), $dbUser);


        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $formUser = $form->getData();

                foreach ($dbUser->getGroups() as $group) {
                    if ($formUser->getGroups()->contains($group)) {
                        $group->removeUser($dbUser);
                        $this->getDoctrine()->getManager()->persist($group);
                    }
                }

                
                foreach ($formUser->getGroups() as $group) {
                    if (!$group->getUsers()->contains($formUser)) {
                        $group->addUser($formUser);
                        $this->getDoctrine()->getManager()->persist($group);
                    }
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($formUser);

                //PASSWORD MUST NOT BE CHANGED IN EDIT
                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                //$password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');
                // $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($formUser);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('usergrid'));
            }
        }

        return $this->render('CodegenUserBundle:User:edituser.html.twig', array(
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

    public function pwdChangeBsAction(Request $request) {

        $user = $this->getUser();

        $form = $this->createForm(new PwdChangeBsType());


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

                // return $this->redirect($this->generateUrl('usergrid'));
                return 'Password modificata';
            }
        }
        return $this->render('CodegenUserBundle:User:pwdchange.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request) {


        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository('CodegenUserBundle:User')->find($id);

        // Remove the record...
        $em->remove($user);

        $em->flush();

        return $this->redirect($this->generateUrl('usergrid'));
    }

    public function userEditAction(Request $request) {

        $id = $request->query->get('id');

        $dbUser = $this->getDoctrine()->getRepository('CodegenUserBundle:User')->find($id);

        $form = $this->createForm(new UserEditBsType(), $dbUser, array(
            'action' => ($this->generateUrl('useredit') . '?id=' . $id),
        ));


        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $formUser = $form->getData();
              
                foreach ($dbUser->getGroups() as $group) {
                    if ($formUser->getGroups()->contains($group)) {
                        $group->removeUser($dbUser);
                        
                        $this->getDoctrine()->getManager()->persist($group);
                    }
                }

                foreach ($formUser->getGroups() as $group) {
                    if (!$group->getUsers()->contains($formUser)) {
                        
                        $group->addUser($formUser);
                        $this->getDoctrine()->getManager()->persist($group);
                    }
                }
                
                //check all groups for removal
                $groups=$this->getDoctrine()->getRepository('CodegenUserBundle:Group')->findAll();
                
                foreach($groups as $group){
                    if(!$formUser->getGroups()->contains($group)){
                         $group->removeUser($dbUser);
                           $this->getDoctrine()->getManager()->persist($group);
                    }
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($formUser);

                //PASSWORD MUST NOT BE CHANGED IN EDIT
                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                //$password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');
                // $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($formUser);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('userlist2b'));
            }
        }


        /*
          $id = $request->query->get('id');

          $user = $this->getDoctrine()->getRepository('CodegenUserBundle:User')->find($id);

          $formedit = $this->createForm(new UserEditBsType(), $user,
          array('action'=>($this->generateUrl('useredit').'?id='.$id)
          ));

          //$formedit = $this->createForm(new UserEditBsType(), $user);

          if ($request->getMethod() === "POST") {

          $formedit->handleRequest($request);
          if ($formedit->isValid()) {
          $user = $formedit->getData();

          foreach ($user->getGroups() as $group) {
          $group->addUser($user);
          $this->getDoctrine()->getManager()->persist($group);
          }

          $factory = $this->get('security.encoder_factory');

          $encoder = $factory->getEncoder($user);

          //PASSWORD MUST NOT BE CHANGED IN EDIT
          //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
          //$password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');
          // $user->setPassword($password);

          $this->getDoctrine()->getManager()->persist($user);

          $this->getDoctrine()->getManager()->flush();

          return $this->redirect($this->generateUrl('userlist2b'));
          }
          }
         */
        return $this->render('CodegenUserBundle:User:userEdit.html.twig', array(
                    'formedit' => $form->createView(),
        ));
    }

    //#0 ajax list users table data controller
    public function userAjaxListDataAction(Request $request) {
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

        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);


//   WORKS     $orderfield = $request->get('orderField');
//        $ordersort = $request->get('orderSort');
//          $ordersort = $request->get('orderSort');
//
//        if (isset($orderfield) && $orderfield !== '' && $orderfield !== null) {
//            $_SESSION['userOrderField'] = $orderfield;
//            $_SESSION['userOrderSort'] = $ordersort;
//            $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array($orderfield => $ordersort), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
//        } else {
//            if (isset($_SESSION['userOrderField'])) {
//                $orderfield = $_SESSION['userOrderField'];
//                $ordersort = $_SESSION['userOrderSort'];
//            } else {
//                $orderfield = 'id';
//                $ordersort = 'DESC';
//            }
//
//
//            $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array($orderfield => $ordersort), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
//        }
        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $numUsers = count($allUsers);



        return $this->render('CodegenUserBundle:User:userAjaxGridData.html.twig', array(
                    'users' => $users,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'page' => $page,
                    'numUsers' => $numUsers,
        ));
    }

    //#1 main page controller
    public function userListPageAction(Request $request) {

        isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 5;
        // $numItemsPerPage = 2;
        $choiceIndex = 0;
        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array(), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $numUsers = count($allUsers);

        $numPages = ceil($numUsers / $numItemsPerPage);
        return $this->render('CodegenUserBundle:User:userAjaxGridData.html.twig', array(
                    'users' => $users,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'numPages' => $numPages,
                    'form' => $form->createView(),
        ));
    }

    // user datagrid pager
    public function userPagerAction(Request $request, $page) {

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

        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray, $orderArray, $numItemsPerPage, ($page - 1) * $numItemsPerPage);

    


        // $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray);

        // $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy($searchArray);
        $numUsers = count($allUsers);

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


        $numPages = ceil($numUsers / $numItemsPerPage);



        return $this->render('CodegenUserBundle:User:userPagerAjax.html.twig', array(
                    'users' => $users,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'numPages' => $numPages,
                    'numUsers' => $numUsers,
                    'page' => $page,
                    'form' => $form->createView(),
        ));
    }

    public function listAction(request $request, $id = null) {

        $task = new User();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find(1);

        $task->addGroup($group);

        $form = $this->createForm(new UserType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                //var_dump($_POST);
                //var_dump($form->get('groups')->getData());
                $user = $form->getData();

                var_dump($user->getGroups());
                //die;

                foreach ($user->getGroups() as $group) {
                    $group->addUser($user);
                    $this->getDoctrine()->getManager()->persist($group);
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');

                $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($user);

                $this->getDoctrine()->getManager()->flush();
            }

            $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        }
        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        return $this->render('CodegenUserBundle:User:listusers.html.twig', array(
                    'users' => $users,
                    'form' => $form->createView(),
        ));
    }

    public function userlistAction(request $request, $id = null) {

        $task = new User();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find(1);

        $task->addGroup($group);

        $form = $this->createForm(new UserType(), $task);

        if ($request->getMethod() === "POST") {

            $form->handleRequest($request);
            if ($form->isValid()) {
                //var_dump($_POST);
                //var_dump($form->get('groups')->getData());
                $user = $form->getData();

                var_dump($user->getGroups());
                //die;

                foreach ($user->getGroups() as $group) {
                    $group->addUser($user);
                    $this->getDoctrine()->getManager()->persist($group);
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');

                $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($user);

                $this->getDoctrine()->getManager()->flush();
            }

            $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        }

        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        return $this->render('CodegenUserBundle:User:userlist.html.twig', array(
                    'users' => $users,
                    'form' => $form->createView(),
        ));
    }

    public function userAjaxAction(Request $request) {
        $numItemsPerPage = $request->get('numberOfRecords');

        $choiceIndex = $request->get('numRecIndex');
        $_SESSION['numRecIndex'] = $choiceIndex;
        $_SESSION['numItemsPerPage'] = $numItemsPerPage;
        if ($request->get('page')) {
            $page = $request->get('page');
        } else {
            $page = 1;
        }

        $_SESSION['page'] = $page;


        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $numUsers = count($allUsers);

        $form = $this->createFormBuilder()
                // ->setAction($this->generateUrl('userlist2'))
                //->setMethod('GET')
                ->add('numRecords', 'choice', array(
                    //'id'=>'choiceNumItems',
                    'choices' => array('5' => '5', '10' => '10', '20' => '20'),
                    'required' => false,
                    'data' => $choiceIndex,
                    'empty_value' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'width:80px',
                        'page' => $page,
                    ),
                        )
                )
                ->getForm();

        return $this->render('CodegenUserBundle:User:userGridData.html.twig', array(
                    'users' => $users,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'page' => $page,
                    'numUsers' => $numUsers,
                    //'numPages' => $numPages,
                    'form' => $form->createView(),
        ));
    }

    public function userlist2Action(Request $request, $page, $id = null) {


        $task = new User();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find(1);

        $task->addGroup($group);

        $form2 = $this->createForm(new UserType(), $task);


        $numItemsPerPage = $request->get('page');

        isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 10;
        // $numItemsPerPage = 2;
        $choiceIndex = 0;

        isset($_SESSION['numRecIndex']) ? $choiceIndex = $_SESSION['numRecIndex'] : $choiceIndex = 0;


        $search_form = $this->createFormBuilder()
                ->setAction($this->generateUrl('userlist2'))
                ->setMethod('POST')
                ->add('txtSearch', 'search', array(
                    //'id'=>'choiceNumItems',

                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cerca...',
                        'style' => 'width:160px',
                    ),
                ))
                ->add('btnSearch', 'submit', array(
                    'label' => 'Cerca',
                    'attr' => array(
                        'class' => 'btn btn-primary',
                    )
                        )
                )
                ->getForm();

        //trying search post
        if ($request->getMethod() === "POST") {

            $search_form->handleRequest($request);
            if ($search_form->isValid()) {
                error_log('bysearch');
                // error_log($_POST["txtSearch"]);
                // error_log($_POST["form_txtSearch"]);
                //doctrine findby:
                //public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

                $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array('firstname' => 'Paolo'), array('firstname' => 'ASC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            }
        } else {

            error_log('normalgh');
            $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
        }

        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $numUsers = count($allUsers);

        $form = $this->createFormBuilder()
                // ->setAction($this->generateUrl('userlist2'))
                //->setMethod('GET')
                ->add('numRecords', 'choice', array(
                    //'id'=>'choiceNumItems',
                    'choices' => array('5' => '5', '10' => '10', '20' => '20'),
                    'required' => false,
                    'empty_value' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'width:80px',
                    ),
                    'data' => $choiceIndex,
                        )
                )
                ->getForm();




        $numPages = ceil($numUsers / $numItemsPerPage);


        return $this->render('CodegenUserBundle:User:userlist2.html.twig', array(
                    'users' => $users,
                    'page' => $page,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'numPages' => $numPages,
                    'numUsers' => $numUsers,
                    'numrecindex' => $choiceIndex,
                    'form' => $form->createView(),
                    'formadd' => $form2->createView(),
                    'searchform' => $search_form->createView()
        ));
    }

    public function userlist2bAction(Request $request, $page, $id = null) {
        
        
       // $searchResult =  $this->getDoctrine()->getRepository('CodegenUserBundle:User')->searchBy('anghileri');
        
       // var_dump($searchResult); die;
        
        $task = new User();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find(1);

        $task->addGroup($group);

        $formAddUser = $this->createForm(new UserType(), $task);

        $formChangeUsersPwd = $this->createForm(new UserPwdChangeType());


        isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 10;

        if ($request->getMethod() === "POST") {

            $formAddUser->handleRequest($request);
            if ($formAddUser->isValid()) {
                $user = $formAddUser->getData();

                foreach ($user->getGroups() as $group) {
                    $group->addUser($user);
                    $this->getDoctrine()->getManager()->persist($group);
                }

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                //$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $password = $encoder->encodePassword($user->getPassword(), 'RANDOMSALT__RANDOMSALT');

                $user->setPassword($password);

                $this->getDoctrine()->getManager()->persist($user);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('userlist2b'));
            }
        }

        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);

        
        
        $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $numUsers = count($allUsers);






        $numPages = ceil($numUsers / $numItemsPerPage);


        return $this->render('CodegenUserBundle:User:userlistbs.html.twig', array(
                    'users' => $users,
                    'numUsers' => $numUsers,
                    'numPages' => $numPages,
                    'allUsers' => $allUsers,
                    'page' => $page,
                    'itemsPerPage' => $numItemsPerPage,
                    'formadd' => $formAddUser->createView(),
                    'formchangepwd' => $formChangeUsersPwd->createView()
        ));
    }

    public function userlist3Action(Request $request, $page, $numrecords, $id = null) {

        //for addnew (to finish implementation)
        $task = new User();

        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Group')->find(1);

        $task->addGroup($group);

        //ane for addnew

        $formadd = $this->createForm(new UserType(), $task);


        //$numItemsPerPage = $request->get('page');
        // $numItemsPerPage = $request->get('numRecords');
        $numItemsPerPage = $numrecords;
        //isset($_SESSION['numItemsPerPage']) ? $numItemsPerPage = $_SESSION['numItemsPerPage'] : $numItemsPerPage = 10;
        // $numItemsPerPage = 2;
        $choiceIndex = 0;

        //  isset($_SESSION['numRecIndex']) ? $choiceIndex = $_SESSION['numRecIndex'] : $choiceIndex = 0;


        $value = null;

        if (isset($_SESSION['userSearchParam'])) {
            $value = $_SESSION['userSearchParam'];
        }

        $search_form = $this->createFormBuilder()
                ->setMethod('POST')
                ->add('txtSearch', 'search', array(
                    //'id'=>'choiceNumItems',

                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cerca...',
                        'style' => 'width:160px',
                        'value' => $value
                    ),
                ))
                ->add('btnSearch', 'submit', array(
                    'label' => ' Cerca',
                    'attr' => array(
                        'class' => 'btn btn-primary',
                    )
                        )
                )
                ->getForm();

        $filter_form = $this->createFormBuilder()
                ->setMethod('POST')
                ->add('btnFilter', 'submit', array(
                    'label' => 'Rimuovi filtro',
                    'attr' => array(
                        'class' => 'btn btn-primary',
                    )
                        )
                )
                ->getForm();

        $p = null;
        //trying search post
        if ($request->getMethod() === "POST") {

            $search_form->handleRequest($request);

            $filter_form->handleRequest($request);
            if ($filter_form->isValid()) {


                $_SESSION['userSearchParam'] = null;


                $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            }

            if ($search_form->isValid()) {

                $_SESSION['userSearchParam'] = $search_form["txtSearch"]->getData();

                //not useful
                //$_SESSION['filter']=$search_form["chkFilter"]->getData();
                //not useful
                //error_log('sessfilter '. $_SESSION['filter']);
                //doctrine findby:
                //public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

                $p = $search_form["txtSearch"]->getData();

                if ($p !== null && $p !== '') {
                    $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array('firstname' => $p), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
                } else {
                    $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
                }
            }
        } else {
            $p = null;

            if (isset($_SESSION['userSearchParam'])) {

                $p = $_SESSION['userSearchParam'];
            }

            if ($p !== null && $p !== '') {

                $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array('firstname' => $p), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            } else {

                $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array(), array('id' => 'DESC'), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            }
        }


        //find all users for counting
        if ($p !== null && $p !== '') {
            $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findBy(array('firstname' => $p), array('firstname' => 'ASC'));
        } else {
            $allUsers = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        }

        $numUsers = count($allUsers);

        $numCurUsers = count($users);

        $form = $this->createFormBuilder()
                // ->setAction($this->generateUrl('userlist2'))
                //->setMethod('GET')
                ->add('numRecords', 'choice', array(
                    //'id'=>'choiceNumItems',
                    'choices' => array('5' => '5', '10' => '10', '20' => '20'),
                    'required' => false,
                    'empty_value' => false,
                    'preferred_choices' => array($numItemsPerPage),
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'width:80px',
                    ),
                    'data' => $choiceIndex,
                        )
                )
                ->getForm();




        $numPages = ceil($numUsers / $numItemsPerPage);


        return $this->render('CodegenUserBundle:User:userlist3.html.twig', array(
                    'users' => $users,
                    'page' => $page,
                    'allUsers' => $allUsers,
                    'itemsPerPage' => $numItemsPerPage,
                    'numPages' => $numPages,
                    'numUsers' => $numUsers,
                    'numCurUsers' => $numCurUsers,
                    'numrecindex' => $choiceIndex,
                    'form' => $form->createView(),
                    'formadd' => $formadd->createView(),
                    'searchform' => $search_form->createView(),
                    'filterform' => $filter_form->createView()
        ));
    }

    public function jsonlistAction(request $request) {
        $request = $this->getRequest();

        $users = $this->getDoctrine()->getManager()->getRepository('CodegenUserBundle:User')->findAll();
        $format = $request->getRequestFormat();
        // $serializedEntity = $this->container->get('serializer')->serialize($users, 'json');
        $response = array();

        $total = 0;
        foreach ($users as $user) {
            $response[] = array('id' => $user->getId(), 'username' => $user->getUsername(), 'email' => $user->getEmail(), 'isActive' => $user->getIsActive());

            $total ++;
        }

        $json = json_encode($response);

        $json = json_decode($json);
        $strTotal = "'" . $total . "'";
//        FOR ZHIXIN
        $jsonAll = array('total' => $total,
            'rows' => $json);




        return $this->render('CodegenUserBundle:Default:base.' . $format . '.twig', array(
                    'data' => $jsonAll));
        die;

        $users = array();
        foreach ($users as $user) {
            $user = array('id' => $user->getId(), 'username' => $user->getUsername(), 'email' => $user->getEmail(), 'isActive' => $user->getIsActive());
        }
        $users = array_push($users, $user);


        $data = array(
            'success' => true,
            'root' => 'users',
            'rows' => $response
        );
    }
    
    public function searchByAction(request $request){
        
    }

}
