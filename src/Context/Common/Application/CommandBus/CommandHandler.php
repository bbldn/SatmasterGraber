<?php

namespace App\Context\Common\Application\CommandBus;

/**
 * @template TCommand
 * @template TReturn
 *
 * @psalm-method TReturn __invoke(TCommand $command)
 */
interface CommandHandler
{
}