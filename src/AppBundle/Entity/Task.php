<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DMS\Filter\Rules as Filter;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
* @ORM\Table(name="task")
*/
class Task
{
	/**
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	* @ORM\Column(type="integer")
	*/
	private $id;

	/**
    * @Filter\StripTags()
    * @Filter\Trim()
    * @Assert\NotBlank()
    * @Assert\Type("string")
    * @ORM\Column(type="string")
    * @var string
    */
	private $name;

	/**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
	private $deadline;

    /**
     * @ORM\ManyToOne(targetEntity="Todo", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $todo;
	
    public function getId()
    {
        return $this->id;
    }
	public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setDeadline(\DateTime $deadline = null)
    {
        $this->deadline = $deadline;
    }
    public function getDeadline()
    {
    	return $this->deadline;
    }
    public function getTodoId($todo)
    {
        return $this->todo;
    }
    public function setTodoId(Todo $todo)
    {
        $this->todo = $todo;
    }
}