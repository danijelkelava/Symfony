<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MainController extends Controller
{
	
    public function homepageAction()
    {
    	/*if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('GET OUT!');
        }*/
        
        // KRACI NACIN
        //$this->denyAccessUnlessGranted('ROLE_USER');

    	$heading = 'Todo App';
        return $this->render('main/homepage.html.twig', [
        	'heading' => $heading
        	]);
    }
}