<?php
namespace GS\FeedbackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Table(name="gs_feedback")
 * @ORM\Entity(repositoryClass="GS\FeedbackBundle\Repository\FeedbackRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"fb_student" = "FbStudent", "fb_client" = "FbClient", "fb_client_denial" = "FbClient_Denial"})
 */

abstract class Feedback
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
      * @var \DateTime
      *
      * @ORM\Column(name="creation_date", type="datetime")
      */
    protected $creationDate;

    /**
      * @var \DateTime
      *
      * @ORM\Column(name="response_date", type="datetime", nullable=true)
      */
    protected $responseDate;

    /**
     * @ORM\OneToOne(targetEntity="Token", mappedBy="feedback", cascade={"persist", "remove"})
     */
    protected $token;

    /**
     * @ORM\ManyToOne(targetEntity="GS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="feedback", cascade={"persist", "remove"})
     */
    private $question;

      public function __construct()
      {
          $this->creationDate = new \DateTime("now", new \DateTimeZone("EUROPE/Paris"));
          $this->question = new ArrayCollection();
      }

      public function isSubmitted()
      {
          return $this->responseDate != null;
      }

      public function stringState()
      {
            if($this->isSubmitted())
                return "Rempli";
            else
                return "Non rempli";
      }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return Feedback
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set responseDate.
     *
     * @param \DateTime|null $responseDate
     *
     * @return Feedback
     */
    public function setResponseDate($responseDate = null)
    {
        $this->responseDate = $responseDate;

        return $this;
    }

    /**
     * Get responseDate.
     *
     * @return \DateTime|null
     */
    public function getResponseDate()
    {
        return $this->responseDate;
    }

    /**
     * Set token.
     *
     * @param \GS\FeedbackBundle\Entity\Token|null $token
     *
     * @return Feedback
     */
    public function setToken(\GS\FeedbackBundle\Entity\Token $token = null)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return \GS\FeedbackBundle\Entity\Token|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set user.
     *
     * @param \GS\UserBundle\Entity\User $user
     *
     * @return Feedback
     */
    public function setUser(\GS\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \GS\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add question.
     *
     * @param \GS\FeedbackBundle\Entity\Question $question
     *
     * @return Feedback
     */
    public function addQuestion(\GS\FeedbackBundle\Entity\Question $question)
    {
        $this->question[] = $question;
        $question->setFeedback($this);

        return $this;
    }

    /**
     * Remove question.
     *
     * @param \GS\FeedbackBundle\Entity\Question $question
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeQuestion(\GS\FeedbackBundle\Entity\Question $question)
    {
        $question->setFeedback(null);
        return $this->question->removeElement($question);
    }

    /**
     * Get question.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
