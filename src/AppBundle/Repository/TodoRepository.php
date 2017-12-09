<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityRepository;


class TodoRepository extends EntityRepository
{
	/**
     * @return Todo[]
     */
	public function findAllTodosOrderedByDate($userId)
    {
    	/*$dql = "SELECT todo FROM AppBundle\Entity\Todo todo ORDER BY todo.dateCreated DESC";
    	$query = $this->getEntityManager()->createQuery($dql);
    	//var_dump($query->getSQL());die;
        return $query->execute();*/
        
        return $this->createQueryBuilder('todo')
                    ->andWhere('todo.user = :user')
                    ->setParameter(':user', $userId)
                    ->orderBy('todo.dateCreated', 'DESC')
                    ->getQuery()
                    ->execute();
    }

    /**
     * @param $todo
     * @return Todo[]
     */
    public function findTodoById($todo)
    {
    	return $this->createQueryBuilder('todo')
            ->andWhere('todo.id = :todo')
            ->setParameter(':todo', $todo)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $id
     * @return Tasks[]
     */
    public function findTasks($id)
    {
        $dql = "SELECT todo.id as todo_id, todo.name as todo_name, task.id as task_id, task.name as task_name, task.deadline as deadline FROM AppBundle\Entity\Todo todo LEFT JOIN todo.tasks task WHERE task.todo=:id ORDER BY task_id DESC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        return $query->execute();
        /*
        return $this->createQueryBuilder('todo')
                    ->leftJoin('todo.tasks', 'tasks')
                    ->andWhere('tasks.todo = :todo')
                    ->setParameter(':todo', $id)
                    ->getQuery()
                    ->execute();*/
    }
}