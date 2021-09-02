<?php

namespace App\Context\Common\Application\QueryBus;

/**
 * @template TQuery
 * @template TReturn
 *
 * @psalm-method TReturn __invoke(TQuery $query)
 */
interface QueryHandler
{
}