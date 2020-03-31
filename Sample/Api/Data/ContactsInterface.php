<?php

namespace Javid\Sample\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ContactsInterface extends ExtensibleDataInterface
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
    public function setEmail($email);

}