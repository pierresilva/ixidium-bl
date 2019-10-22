<?php

/*
 * This file is part of Business.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Business\Tests;

use Business\DateRange;

class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testIterator()
    {
        $dateRange = new DateRange(new \DateTime('2015-07-08'), new \DateTime('2015-07-13'));

        $this->assertInstanceOf('ArrayIterator', $dateRange->getIterator());

        $expected = [
            '2015-07-08',
            '2015-07-09',
            '2015-07-10',
            '2015-07-11',
            '2015-07-12',
            '2015-07-13',
        ];
        $actual = [];

        foreach ($dateRange as $dateTime) {
            $actual[] = $dateTime->format('Y-m-d');
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Start date must be earlier than end date.
     */
    public function testStartDateIsEarlier()
    {
        new DateRange(new \DateTime('2015-07-08'), new \DateTime('2015-07-07'));
    }
}
