<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use APY\DataGridBundle\Grid\Source\Vector;
use APY\DataGridBundle\Grid\Column\TextColumn;


class UsergridController extends Controller {

    public function indexAction() {
        // Data Source initialization -- SEE FOR JOINED TABLE
//         $em = $this->getDoctrine()->getManager();
//        $query = $em->createquery('SELECT u,r
//                FROM CodegenUserBundle:User u
//                 JOIN u role r');
//        $user=$query->getResult();

//        $user = $this->getDoctrine()
//                ->getRepository('CodegenUserBundle:User')
//                ->findByRole(1);
//
//        //$source = new Vector($user);
//        $role= $user->getRole();
        //comment to display other source
        $source = new Entity('CodegenUserBundle:User');

        // Get grid service
        $grid = $this->container->get('grid');

        $grid->addMassAction(new DeleteMassAction());


        //$grid->addColumn(new TextColumn(['id' => 'role.description',  'field' => 'role.description', 'title' => 'Ruoloxx']));
        //action view
        $rowAction = new RowAction("Edit", 'usergrid_edit');
        $grid->addRowAction($rowAction);

        // action delete
        $rowAction = new RowAction("Delete", 'usergrid_delete', true);
        // $rowAction->setConfirmMessaeg('Are you sure you want to delete this user?');
        $grid->addRowAction($rowAction);

        // Set source
        $grid->setSource($source);
        $grid->setLimits(array(5, 10, 15, 20, 25));
        $grid->setDefaultLimit(5);
        $grid->addExport(new CSVExport('Esporta in CSV'));
        $grid->addExport(new ExcelExport('Esporta in Excel'));
        
       $userColumns = array('id', 'username', 'role.description','email','isActive');
       $grid->setColumnsOrder($userColumns);
       $grid->setVisibleColumns($userColumns);
        return $grid->getGridResponse('CodegenUserBundle:User:usergrid.html.twig');
    }

    public function deleteAction(Request $request) {

        //$user = $this->getUser();

        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository('CodegenUserBundle:Usergrid')->find($id);

        // Remove the record...
        $em->remove($user);
        $em->flush();



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

        return $this->redirect($this->generateUrl('usergrid'));
        //return $this->render('CodegenUserBundle:User:usergrid.html.twig', array(
        // 'form' => $form->createView(),
        //));
    }

}
