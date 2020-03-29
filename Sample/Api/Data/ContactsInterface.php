<?php

namespace Javid\Sample\Api\Data;

interface ContactsInterface
{
    /**
     * @return int
     */
    public function getPfayContactsId();

    /**
     * @param int $pfayContactId
     * @return void
     */
    public function setPfayContactsId($pfayContactId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return void
     */
    public function setEmail();

}