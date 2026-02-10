<?php

namespace SprykerAcademy\Zed\HelloWorld\Persistence;

use Generated\Shared\Transfer\MessageCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;

interface HelloWorldRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageCriteriaTransfer $messageCriteria
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findMessage(MessageCriteriaTransfer $messageCriteria): ?MessageTransfer;
}
