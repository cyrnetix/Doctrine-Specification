<?php
declare(strict_types=1);

/**
 * This file is part of the Happyr Doctrine Specification package.
 *
 * (c) Tobias Nyholm <tobias@happyr.com>
 *     Kacper Gunia <kacper@gunia.me>
 *     Peter Gribanov <info@peter-gribanov.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Happyr\DoctrineSpecification\Filter;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\Filter\Filter;
use Happyr\DoctrineSpecification\Filter\IsNull;
use PhpSpec\ObjectBehavior;

/**
 * @mixin IsNull
 */
final class IsNullSpec extends ObjectBehavior
{
    private $field = 'foobar';

    private $context = 'a';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->context);
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(Filter::class);
    }

    /**
     * returns expression func object.
     */
    public function it_calls_null(QueryBuilder $qb, Expr $expr): void
    {
        $expression = 'a.foobar is null';

        $qb->expr()->willReturn($expr);
        $expr->isNull(sprintf('%s.%s', $this->context, $this->field))->willReturn($expression);

        $this->getFilter($qb, 'b')->shouldReturn($expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $qb, Expr $expr): void
    {
        $context = 'x';
        $this->beConstructedWith($this->field, null);
        $qb->expr()->willReturn($expr);

        $expr->isNull(sprintf('%s.%s', $context, $this->field))->shouldBeCalled();
        $this->getFilter($qb, $context);
    }
}
