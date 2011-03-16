<?php

namespace Bundle\LichessBundle\Document;

use FOS\UserBundle\Model\User;
use DateTime;

/**
 * Represents a single timeline entry, pre-rendered
 *
 * @mongodb:Document(
 *   collection="timeline",
 *   repositoryClass="Bundle\LichessBundle\Document\TimelineEntryRepository"
 * )
 */
class TimelineEntry
{
    /**
     * Unique ID of the game
     *
     * @var string
     * @mongodb:Id()
     */
    protected $id;

    /**
     * Type of the event
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $type;

    /**
     * Rendered event
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $html;

    /**
     * Author user if any
     *
     * @var User
     * @mongodb:ReferenceOne(targetDocument="Application\UserBundle\Document\User")
     */
    protected $author;

    /**
     * Date of creation
     *
     * @var DateTime
     * @mongodb:Field(type="date")
     * @mongodb:Index(order="desc")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param  User
     * @return null
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }
    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param  string
     * @return null
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string
     * @return null
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param  DateTime
     * @return null
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
