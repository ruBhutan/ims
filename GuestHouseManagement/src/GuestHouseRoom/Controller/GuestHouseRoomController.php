<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GuestHouseRoom\Controller;


//use Blog\Form\Add;
//use Blog\InputFilter\AddPost;
//use Blog\Entity\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GuestHouseRoom\Form\AddGuestHouseRoomForm;




/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class GuestHouseRoomController extends AbstractActionController
{
    public function addguesthouseroomAction()
    {
        $form = new AddGuestHouseRoomForm();
        
        return new ViewModel(array(
            'form' => $form,
        ));
    }           
}
