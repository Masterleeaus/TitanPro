<?php

declare(strict_types=1);

namespace Modules\Dispatch\Tests\Unit;

use InvalidArgumentException;
use Modules\Dispatch\Support\DTOs\ScheduleWindow;
use PHPUnit\Framework\TestCase;

class ScheduleWindowTest extends TestCase
{
    public function test_window_calculates_duration(): void
    {
        $window = ScheduleWindow::fromStrings('2026-05-13 09:00:00', '2026-05-13 10:30:00');

        self::assertSame(90, $window->minutes());
    }

    public function test_window_rejects_negative_duration(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ScheduleWindow::fromStrings('2026-05-13 10:00:00', '2026-05-13 09:00:00');
    }

    public function test_overlap_detection(): void
    {
        $first = ScheduleWindow::fromStrings('2026-05-13 09:00:00', '2026-05-13 10:00:00');
        $second = ScheduleWindow::fromStrings('2026-05-13 09:30:00', '2026-05-13 11:00:00');

        self::assertTrue($first->overlaps($second));
    }
}
