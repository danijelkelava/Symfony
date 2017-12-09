<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityRepository;


class TaskRepository extends EntityRepository
{
	/**
     * @return Task[]
     */
	public function findAllTasks($id)
    {
        /*$dql = "SELECT todo FROM AppBundle\Entity\Todo todo LEFT JOIN todo.task WHERE todo.id='$id'";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->execute();*/

    	/*$dql = "SELECT task FROM AppBundle\Entity\Task task WHERE task.todo=:id ORDER BY task.name ASC";
    	$query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        return $query->execute();*/
        
        return $this->createQueryBuilder('task')
                    ->andWhere('task.todo = :todo')
                    ->setParameter(':todo', $id)
                    ->orderBy('task.id', 'DESC')
                    ->getQuery()
                    ->execute();
    }
}