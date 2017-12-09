<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\AdminSearchFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/", name="admin_genus_list")
 * @Security("is_granted('ROLE_MANAGE_USERS')")
 */
class AdminController extends Controller 
{
	private $word;

	/**
     * @Route("/admin", name="admin_section")
     */
	public function adminAction(Request $request)
	{
		/*if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('GET OUT!');
        }*/

        // KRACI NACIN
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$form = $this->createForm(AdminSearchFormType::class);
		$heading = "Admin Section";

		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            

            $this->addFlash('success', 'User Edited!');
            return $this->redirectToRoute('admin_section');
        }
		return $this->render('admin/admin.html.twig',[
			'heading'   => $heading,
			'adminSearchForm' => $form->createView()
			]);
	}
    
    /**
     * @Route("/admin/search", name="search_user")
     */
	public function handleSearch(Request $request)
	{
		$heading = "Search Result";

		if ($request->getMethod() == Request::METHOD_POST){
	        $word = $request->request->get('admin_search_form')['_search'];
        }

		$user = $this->getDoctrine()
		             ->getRepository('AppBundle:User')
		             ->findUsers($word);

		return $this->render('admin/search.html.twig' ,[
			'user' => $user,
			'heading'   => $heading			
			]);
	}
    
    /**
     * @Route("/admin/update/{id}", name="update_user")
     */
	public function updateAction(Request $request)
	{
		die('hi');
	}

}