<?php

namespace GS\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mail
 *
 * @ORM\Table(name="gs_new_mail")
 * @ORM\Entity(repositoryClass="GS\MailBundle\Repository\MailRepository")
 */
class Mail implements \JsonSerializable
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
     * @var string|null
     *
     * @ORM\Column(name="recipientEmail", type="string", length=255, nullable=true)
     */
    private $recipientEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fromEmail", type="string", length=255, nullable=true)
     */
    private $fromEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fromAlias", type="string", length=255, nullable=true)
     */
    private $fromAlias;

    /**
     * @var string|null
     *
     * @ORM\Column(name="replyToEmail", type="string", length=255, nullable=true)
     */
    private $replyToEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="plainText", type="boolean", nullable=true)
     */
    private $plainText;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="sentDate", type="datetime", nullable=true)
     */
    private $sentDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="scheduledDate", type="datetime", nullable=true)
     */
    private $scheduledDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attachmentPath", type="string", length=255, nullable=true)
     */
    private $attachmentPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attachmentName", type="string", length=255, nullable=true)
     */
    private $attachmentName;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="error", type="boolean", nullable=true)
     */
    private $error;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="artificial", type="boolean", nullable=true)
     */
    private $artificial;



    public function __construct(){
        $this->creationDate = new \DateTime("now", new \DateTimeZone("EUROPE/Paris"));
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function isSent(){
        return $this->sentDate != null;
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
     * Set recipientEmail.
     *
     * @param string|null $recipientEmail
     *
     * @return Mail
     */
    public function setRecipientEmail($recipientEmail = null)
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    /**
     * Get recipientEmail.
     *
     * @return string|null
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     * Set fromEmail.
     *
     * @param string|null $fromEmail
     *
     * @return Mail
     */
    public function setFromEmail($fromEmail = null)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Get fromEmail.
     *
     * @return string|null
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set fromAlias.
     *
     * @param string|null $fromAlias
     *
     * @return Mail
     */
    public function setFromAlias($fromAlias = null)
    {
        $this->fromAlias = $fromAlias;

        return $this;
    }

    /**
     * Get fromAlias.
     *
     * @return string|null
     */
    public function getFromAlias()
    {
        return $this->fromAlias;
    }

    /**
     * Set replyToEmail.
     *
     * @param string|null $replyToEmail
     *
     * @return Mail
     */
    public function setReplyToEmail($replyToEmail = null)
    {
        $this->replyToEmail = $replyToEmail;

        return $this;
    }

    /**
     * Get replyToEmail.
     *
     * @return string|null
     */
    public function getReplyToEmail()
    {
        return $this->replyToEmail;
    }

    /**
     * Set object.
     *
     * @param string|null $object
     *
     * @return Mail
     */
    public function setObject($object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object.
     *
     * @return string|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set content.
     *
     * @param string|null $content
     *
     * @return Mail
     */
    public function setContent($content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime|null $creationDate
     *
     * @return Mail
     */
    public function setCreationDate($creationDate = null)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime|null
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set sentDate.
     *
     * @param \DateTime|null $sentDate
     *
     * @return Mail
     */
    public function setSentDate($sentDate = null)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate.
     *
     * @return \DateTime|null
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * Set scheduledDate.
     *
     * @param \DateTime|null $scheduledDate
     *
     * @return Mail
     */
    public function setScheduledDate($scheduledDate = null)
    {
        $this->scheduledDate = $scheduledDate;

        return $this;
    }

    /**
     * Get scheduledDate.
     *
     * @return \DateTime|null
     */
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * Set subject.
     *
     * @param string|null $subject
     *
     * @return Mail
     */
    public function setSubject($subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set plainText.
     *
     * @param bool|null $plainText
     *
     * @return Mail
     */
    public function setPlainText($plainText = null)
    {
        $this->plainText = $plainText;

        return $this;
    }

    /**
     * Get plainText.
     *
     * @return bool|null
     */
    public function getPlainText()
    {
        return $this->plainText;
    }

    /**
     * Set attachmentPath.
     *
     * @param string|null $subject
     *
     * @return Mail
     */
    public function setAttachmentPath($attachmentPath = null)
    {
        $this->attachmentPath = $attachmentPath;

        return $this;
    }

    /**
     * Get attachmentPath.
     *
     * @return string|null
     */
    public function getAttachmentPath()
    {
        return $this->attachmentPath;
    }

    /**
     * Set attachmenName.
     *
     * @param string|null $subject
     *
     * @return Mail
     */
    public function setAttachmentName($attachmentName = null)
    {
        $this->attachmentName = $attachmentName;

        return $this;
    }

    /**
     * Get attachmentName.
     *
     * @return string|null
     */
    public function getAttachmentName()
    {
        return $this->attachmentName;
    }

    /**
     * Set error.
     *
     * @param bool|null $plainText
     *
     * @return Mail
     */
    public function setError($error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error.
     *
     * @return bool|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set artificial.
     *
     * @param bool|null
     *
     * @return Mail
     */
    public function setArtificial($artificial = null)
    {
        $this->artificial = $artificial;

        return $this;
    }

    /**
     * Get artificial.
     *
     * @return bool|null
     */
    public function getArtificial()
    {
        return $this->artificial;
    }

}
