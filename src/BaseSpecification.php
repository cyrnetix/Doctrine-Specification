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

namespace Happyr\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\Filter\Filter;
use Happyr\DoctrineSpecification\Query\QueryModifier;
use Happyr\DoctrineSpecification\Specification\Specification;

/**
 * Extend this abstract class if you want to build a new spec with your domain logic.
 */
abstract class BaseSpecification implements Specification
{
    /**
     * @var string|null
     */
    private $context;

    /**
     * @param string|null $context
     */
    public function __construct($context = null)
    {
        $this->context = $context;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $context
     *
     * @return string
     */
    public function getFilter(QueryBuilder $qb, string $context): string
    {
        $spec = $this->getSpec();

        if ($spec instanceof Filter) {
            return $spec->getFilter($qb, $this->getContext($context));
        }

        return '';
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $context
     */
    public function modify(QueryBuilder $qb, string $context): void
    {
        $spec = $this->getSpec();

        if ($spec instanceof QueryModifier) {
            $spec->modify($qb, $this->getContext($context));
        }
    }

    /**
     * Return all the specifications.
     *
     * @return Filter|QueryModifier
     */
    abstract protected function getSpec();

    /**
     * @param string $context
     *
     * @return string
     */
    private function getContext(string $context): string
    {
        if (null !== $this->context) {
            return $this->context;
        }

        return $context;
    }
}
