<?php

namespace Api\Lib\Utils;

use DateTime;

class Date
{
    private $date;

    public function __construct(string $date)
    {
        $this->date = date('Y-m-d', strtotime($date));
    }

    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function today(): string
    {
        return date('Y-m-d');
    }

    public static function getIsoFormat(string $dateTime): string
    {
        $strtotime = strtotime($dateTime);
        $date = date('Y-m-d', $strtotime);
        $time = date('H:i:s', $strtotime);

        return implode('', [$date, 'T', $time, '.000Z']);
    }

    public function getPureSumDateTime(int $qtd, string $type)
    {
        return date('Y-m-d H:i:s', strtotime($this->date . ' + ' . $qtd . ' ' . $type));
    }

    public function isPastDate(): bool
    {
        return $this->date < date('Y-m-d');
    }

    public function getDaysDifferenceToToday(): int
    {
        return $this->getDaysDifference(date('Y-m-d'), $this->date);
    }

    public function getDaysDifference(string $startDate, string $endDate): int
    {
        $datetime1 = new DateTime($endDate);
        $datetime2 = new DateTime($startDate);
        $interval = $datetime1->diff($datetime2, true);
        $intervalDays = $interval->days;
        if ($interval->h > 0) {
            $intervalDays = $intervalDays + 1;
        }
        return $intervalDays;
    }

    public function getPureSubtractDate(int $qtd, string $type)
    {
        if ($this->isLastDayMonthAndPeriodicityMonthly($type)) {
            return $this->getDateLastDayNextMonth();
        }
        return date('Y-m-d', strtotime($this->date . ' - ' . $qtd . ' ' . $type));
    }

    private function getDateLastDayNextMonth(): string
    {
        $info = $this->getNextDayMonthAndYear();
        $nextDate = date(implode('-', array_reverse($info)));
        $lastDay = date('t', strtotime($nextDate));
        $info['day'] = $lastDay;
        return date(implode('-', array_reverse($info)));
    }

    private function getNextDayMonthAndYear(): array
    {
        $currentMonth = date('m', strtotime($this->date));
        $nextMonth = $currentMonth + 1;
        $currentYear = date('Y', strtotime($this->date));
        if ($this->isLastMonthYear()) {
            $nextMonth = 1;
            $currentYear++;
        }
        $firstDayMonth = str_pad(1, 2, "0", STR_PAD_LEFT);
        return ['day' => $firstDayMonth, 'month' => str_pad($nextMonth, 2, "0", STR_PAD_LEFT), 'year' => $currentYear];
    }

    private function isLastMonthYear(): bool
    {
        $currentMonth = date('m', strtotime($this->date));
        return $currentMonth == '12';
    }

    private function isLastDayMonthAndPeriodicityMonthly(string $type): bool
    {
        $currentDay = date('d', strtotime($this->date));
        $lastDayCurrentMonth = date('t', strtotime($this->date));
        if ($currentDay != $lastDayCurrentMonth) {
            return false;
        }
        return $this->isMontlhyPeriodicity($type);
    }

    private function isMontlhyPeriodicity(string $type): bool
    {
        return strtoupper($type) == 'MONTHS';
    }
}