<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GuestRoomBookApproval\Controller;


//use Blog\Form\Add;
//use Blog\InputFilter\AddPost;
//use Blog\Entity\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use BookGuestHouse\Form\BookGuestHouseRoomForm;




/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class GuestRoomBookApprovalController extends AbstractActionController
{
    public function viewguestroombookAction()
    {
        return new ViewModel();
    }           
}
