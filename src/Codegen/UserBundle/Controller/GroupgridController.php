<?php

namespace Codegen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use APY\DataGridBundle\Grid\Column\TextColumn;


class GroupgridController extends Controller {

    public function indexAction() {
       

        $user = $this->getDoctrine()
                ->getRepository('CodegenUserBundle:Group');
               

        $source = new Entity('CodegenUserBundle:Group');

        // Get grid service
        $grid = $this->container->get('grid');

        $grid->addMassAction(new DeleteMassAction());


      
        //action view
        $rowAction = new RowAction("Edit", 'groupgrid_edit');
        $grid->addRowAction($rowAction);

        // action delete
        $rowAction = new RowAction("Delete", 'groupgrid_delete', true);
        // $rowAction->setConfirmMessage('Are you sure you want to delete this group?');
        $grid->addRowAction($rowAction);

        // Set source
        $grid->setSource($source);
        $grid->setLimits(array(5, 10, 15, 20, 25));
        $grid->setDefaultLimit(5);
        $grid->addExport(new CSVExport('Esporta in CSV'));
        $grid->addExport(new ExcelExport('Esporta in Excel'));
        

        return $grid->getGridResponse('CodegenUserBundle:Group:groupgrid.html.twig');
    }

    public function deleteAction(Request $request) {


        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $group = $this->getDoctrine()->getRepository('CodegenUserBundle:Groupgrid')->find($id);

        // Remove the record...
        $em->remove($group);
        $em->flush();

        return $this->redirect($this->generateUrl('groupgrid'));
        
    }

}
