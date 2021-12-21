<?php

namespace App\Domain\Common\Application\CommandBus;

/**
 * @template TCommand
 * @template TReturn
 *
 * @psalm-method TReturn __invoke(TCommand $command)
 */
interface CommandHandler
{
}