<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DMS\Filter\Rules as Filter;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\TodoRepository")
* @ORM\Table(name="todo")
*/
class Todo
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
     * @ORM\Column(type="datetime")
     */
	private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="todos")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="todo")
     */
    private $tasks;

    public function setTasks()
    {
        $this->tasks = $tasks;
    }
	
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
    public function __construct()
    {
        $this->dateCreated = new \DateTime();
    }
    public function getDate()
    {
    	return $this->dateCreated;
    }
    
    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    public function getUserId($user)
    {
        return $this->user;
    }
    public function setUserId(User $user)
    {
        $this->user = $user;
    }
}