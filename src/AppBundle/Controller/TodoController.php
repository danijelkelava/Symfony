<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Todo;
use AppBundle\Entity\Task;
use AppBundle\Form\TodoFormType;
use AppBundle\Form\TaskFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/")
 */
class TodoController extends Controller
{
    /**
     * @Route("/todos/new", name="todos_new")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(TodoFormType::class);
        $heading = "Add New Todo";
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        //$userId =  $user->getId();
        //var_dump($userId);die();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $todo->setUserId($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            

            $this->addFlash('success', sprintf('Todo created by you: %s!', $this->getUser()->getUsername()));
            return $this->redirectToRoute('todos_list');
        }
        return $this->render('todos/new.html.twig', [
            'todoForm'=> $form->createView(),
            'heading' => $heading
            ]);
    }

    /**
     * @Route("/todos", name="todos_list")
     * 
     */
    public function listAction()
    {
        /*if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('GET OUT!');
        }*/
        
        // KRACI NACIN
        //$this->denyAccessUnlessGranted('ROLE_USER');

        /*$userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        var_dump($userRepo->findOneByUsernameOrEmail('dykyprod@gmail.com'));die;*/
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $userId =  $user->getId();

        $todos = $this->getDoctrine()
                      ->getRepository('AppBundle:Todo')
                      ->findAllTodosOrderedByDate($userId);

        $heading = "Todo List";
        
        return $this->render('todos/list.html.twig', [
            'todos'  => $todos,
            'heading'=> $heading
            ]);
    }

    /**
     * @Route("/todos/todo/{id}/edit", name="todo_edit")
     */
    public function editAction(Request $request, Todo $todo)
    {
        $form = $this->createForm(TodoFormType::class, $todo);
        // only handles data on POST
        $heading = "Edit Todo";
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $this->addFlash('success', 'Todo updated!');
            return $this->redirectToRoute('todos_list');
        }
        return $this->render('todos/edit.html.twig', [
            'todoForm' => $form->createView(),
            'heading'  => $heading
        ]);
    }

    /**
     * @Route("/todos/todo/{id}", name="todo_preview")
     */
    public function previewAction(Request $request, Todo $todo, $id)
    {                
        $heading = "Preview Todo";

        /*foreach ($todo->getTasks() as $task) {
            var_dump($task);
        }*/

        $tasks = $this->getDoctrine()
                       ->getRepository('AppBundle:Todo')
                       ->findTasks($id);
        //var_dump($tasks);die;
        /*$todoId = $this->getDoctrine()
                       ->getRepository('AppBundle:Todo')
                       ->find($todo);*/

        $form = $this->createForm(TaskFormType::class); 

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setTodoId($todo);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();            

            $this->addFlash('success', 'Task created!');
            return $this->redirectToRoute('todo_preview', ['id'=>$id]);
        }        
        return $this->render('todos/preview.html.twig', [
            'tasks'    => $tasks,
            'taskForm' => $form->createView(),
            'heading'  => $heading
        ]);
    }

    /**
     * @Route("/todos/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction(Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo')->find($todo);  
        /*foreach ($todo as $t) {
            $em->remove($t);
        }*/
        $em->remove($todo);
        $em->flush();  

        $this->addFlash('success', 'Todo Removed');

        return $this->redirectToRoute('todos_list');           
    }

    /**
     * @Route("/todos/todo/{todoId}/task/delete/{id}", name="task_delete")
     */
    public function deleteTaskAction(Task $task, $todoId)
    {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('AppBundle:Task')->find($task);  

        $em->remove($task);
        $em->flush();  

        $this->addFlash('success', 'Task Removed');

        return $this->redirectToRoute('todo_preview', ['id'=>$todoId]);           
    }

    /**
     * @Route("/todos/{name}", name="tasks_show_list")
     * @Method("GET")
     */

    public function getTasksAction(Todo $todo)
    {
        $tasks = [];
        foreach ($todo->getTasks() as $task) {
            $tasks[] = [
                'id' => $task->getId(),
                'name'=> $task->getName(),
                'deadline' => $task->getDeadline(),
                'todo_id' => $_GET['id']   
            ];           
        }

        $data = [
              'tasks' => $tasks
        ];

        return new JsonResponse($data);
    }   
}