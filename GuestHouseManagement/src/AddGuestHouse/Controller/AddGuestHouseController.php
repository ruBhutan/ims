<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AddGuestHouse\Controller;


//use Blog\Form\Add;
//use Blog\InputFilter\AddPost;
//use Blog\Entity\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AddGuestHouse\Form\AddGuestHouseForm;




/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class AddGuestHouseController extends AbstractActionController
{
    public function addguesthouseAction()
    {
        $form = new AddGuestHouseForm();
        
        return new ViewModel(array(
            'form' => $form,
        ));
    }           
}
