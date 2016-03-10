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


class RolegridController extends Controller {

    public function indexAction() {
        // Data Source initialization -- SEE FOR JOINED TABLE
//         $em = $this->getDoctrine()->getManager();
//        $query = $em->createquery('SELECT u,r
//                FROM CodegenUserBundle:User u
//                 JOIN u role r');
//        $user=$query->getResult();

        $user = $this->getDoctrine()
                ->getRepository('CodegenUserBundle:Role');
                //->findByRole(1);

        //$source = new Vector($user);
        //$role= $product->getRole();
        //comment to display other source
        $source = new Entity('CodegenUserBundle:Role');

        // Get grid service
        $grid = $this->container->get('grid');

        $grid->addMassAction(new DeleteMassAction());


        //$grid->addColumn(new TextColumn(['id' => 'role.descriptiom', 'title' => 'Role']));
        //action view
        $rowAction = new RowAction("Edit", 'rolegrid_edit');
        $grid->addRowAction($rowAction);

        // action delete
        $rowAction = new RowAction("Delete", 'rolegrid_delete', true);
        // $rowAction->setConfirmMessaeg('Are you sure you want to delete this user?');
        $grid->addRowAction($rowAction);

        // Set source
        $grid->setSource($source);
        $grid->setLimits(array(5, 10, 15, 20, 25));
        $grid->setDefaultLimit(5);
        $grid->addExport(new CSVExport('Esporta in CSV'));
        $grid->addExport(new ExcelExport('Esporta in Excel'));
        
//        $userColumns = array('username', 'id', 'email', 'role.description', 'isActive');
//       $grid->setColumnsOrder($userColumns);
//       $grid->setVisibleColumns($userColumns);
        return $grid->getGridResponse('CodegenUserBundle:Role:rolegrid.html.twig');
    }

    public function deleteAction(Request $request) {


        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $role = $this->getDoctrine()->getRepository('CodegenUserBundle:Rolegrid')->find($id);

        // Remove the record...
        $em->remove($role);
        $em->flush();

        return $this->redirect($this->generateUrl('rolegrid'));
        
    }

}
