<?php

namespace Doctrine\Tests\DBAL\Query\Expression;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\Deprecations\PHPUnit\VerifyDeprecations;
use Doctrine\Tests\DbalTestCase;

class CompositeExpressionTest extends DbalTestCase
{
    use VerifyDeprecations;

    public function testCount(): void
    {
        $this->expectNoDeprecationWithIdentifier('https://github.com/doctrine/dbal/issues/3844');
        $this->expectNoDeprecationWithIdentifier('https://github.com/doctrine/dbal/pull/3864');

        $expr = CompositeExpression::or('u.group_id = 1');

        self::assertCount(1, $expr);

        $expr = $expr->with('u.group_id = 2');

        self::assertCount(2, $expr);
    }

    public function testAdd(): void
    {
        $this->expectDeprecationWithIdentifier('https://github.com/doctrine/dbal/pull/3864');
        $this->expectDeprecationWithIdentifier('https://github.com/doctrine/dbal/issues/3844');

        $expr = CompositeExpression::or('u.group_id = 1');

        self::assertCount(1, $expr);

        $expr->add(new CompositeExpression(CompositeExpression::TYPE_AND, []));

        self::assertCount(1, $expr);

        $expr->add(CompositeExpression::or('u.user_id = 1'));

        self::assertCount(2, $expr);

        $expr->add(null);

        self::assertCount(2, $expr);

        $expr->add('u.user_id = 1');

        self::assertCount(3, $expr);
    }

    public function testWith(): void
    {
        $expr = CompositeExpression::or('u.group_id = 1');

        self::assertCount(1, $expr);

        // test immutability
        $expr->with(CompositeExpression::or('u.user_id = 1'));

        self::assertCount(1, $expr);

        $expr = $expr->with(CompositeExpression::or('u.user_id = 1'));

        self::assertCount(2, $expr);

        $expr = $expr->with('u.user_id = 1');

        self::assertCount(3, $expr);
    }

    /**
     * @param string[]|CompositeExpression[] $parts
     *
     * @dataProvider provideDataForConvertToString
     */
    public function testCompositeUsageAndGeneration(string $type, array $parts, string $expects): void
    {
        $this->expectDeprecationWithIdentifier('https://github.com/doctrine/dbal/pull/3864');

        $expr = new CompositeExpression($type, $parts);

        self::assertEquals($expects, (string) $expr);
    }

    /**
     * @return mixed[][]
     */
    public static function provideDataForConvertToString(): iterable
    {
        return [
            [
                CompositeExpression::TYPE_AND,
                ['u.user = 1'],
                'u.user = 1',
            ],
            [
                CompositeExpression::TYPE_AND,
                ['u.user = 1', 'u.group_id = 1'],
                '(u.user = 1) AND (u.group_id = 1)',
            ],
            [
                CompositeExpression::TYPE_OR,
                ['u.user = 1'],
                'u.user = 1',
            ],
            [
                CompositeExpression::TYPE_OR,
                ['u.group_id = 1', 'u.group_id = 2'],
                '(u.group_id = 1) OR (u.group_id = 2)',
            ],
            [
                CompositeExpression::TYPE_AND,
                [
                    'u.user = 1',
                    CompositeExpression::or(
                        'u.group_id = 1',
                        'u.group_id = 2'
                    ),
                ],
                '(u.user = 1) AND ((u.group_id = 1) OR (u.group_id = 2))',
            ],
            [
                CompositeExpression::TYPE_OR,
                [
                    'u.group_id = 1',
                    CompositeExpression::and(
                        'u.user = 1',
                        'u.group_id = 2'
                    ),
                ],
                '(u.group_id = 1) OR ((u.user = 1) AND (u.group_id = 2))',
            ],
        ];
    }
}
