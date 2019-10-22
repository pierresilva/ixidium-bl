<?php

namespace App\Helpers;

use App\Http\Controllers\HollydaysController;
use Carbon\Carbon;

class BusinessDays
{
  /** @var array */
  protected $weekendDays = [
    // Carbon::SATURDAY+1, Carbon::SUNDAY+1
  ];

  /**
   * @var bool
   */
  public $useHollyDaysCalendar = true;

  /** @var array */
  protected $holidays = [];

  /** @var array */
  protected $closedDays = [];

  /**
   * Establece los dias que son fin de semana, no hábiles de la semana
   *
   * @param array $weekendDays
   * @return BusinessDays
   */
  public function setWeekendDays(array $weekendDays)
  {
    $this->weekendDays = array_map(function ($day) {
      return $day + 1;
    }, $weekendDays);

    return $this;
  }

  /**
   * Agrega un dia festivo, no hábil
   *
   * @param Carbon $date
   * @param bool $checkHollyDaysCalendar
   * @return BusinessDays
   */
  public function addHoliday(Carbon $date, $checkHollyDaysCalendar = true)
  {
    if (!$this->isHoliday($date, $checkHollyDaysCalendar)) {
      $this->holidays[] = $date->format("Ymd");
    }

    return $this;
  }

  /**
   * Agrega fechas de dias festivos, no hábiles
   *
   * @param Carbon ...$dates
   * @return BusinessDays
   */
  public function addHolidays(Carbon ...$dates)
  {
    foreach ($dates as $date) {
      $this->addHoliday($date);
    }

    return $this;
  }

  /**
   * Quita fechas de dias festivos
   *
   * @param Carbon $date
   * @return BusinessDays
   */
  public function removeHoliday(Carbon $date)
  {
    if ($k = array_search($date->format("Ymd"), $this->holidays) !== false) {
      array_splice($this->holidays, $k, 1);
    }

    return $this;
  }

  /**
   * Devuelve true si la fecha es un día establecido como fin de semana, no laboral
   *
   * @param Carbon $day
   * @return bool
   */
  public function isWeekendDay(Carbon $day)
  {
    return array_search($day->dayOfWeek + 1, $this->weekendDays) !== false;
  }

  /**
   * Devuelve true si la fecha es un dia festivo
   *
   * @param Carbon $date
   * @param bool $checkHollyDaysCalendar
   * @return bool
   */
  public function isHoliday(Carbon $date, $checkHollyDaysCalendar = true)
  {

    if ($checkHollyDaysCalendar) {
      $hollyDays = new Hollydays();
      if ($hollyDays->esFestivo($date->day, $date->month, $date->year)) {
        return true;
      }
    }

    return array_search($date->format("Ymd"), $this->holidays) !== false;
  }

  /**
   * Agrega un rango de fechas en que no se atiende al publico
   *
   * @param Carbon $from
   * @param Carbon $to
   * @return BusinessDays
   */
  public function addClosedPeriod(Carbon $from, Carbon $to)
  {
    for ($date = $from->copy(); $date <= $to; ) {
      if (!$this->isClosed($date)) {
        $this->closedDays[] = $date->format("Ymd");
      }
      $date = $date->addDay();
    }

    return $this;
  }

  /**
   * Agrega un día no hábil
   *
   * @param Carbon $date
   */
  public function addClosedDay(Carbon $date)
  {
    $this->closedDays[] = $date->format("Ymd");
  }

  /**
   * Quitas fechas de no hábiles
   *
   * @param Carbon $date
   * @return BusinessDays
   */
  public function removeClosedDay(Carbon $date)
  {
    if ($k = array_search($date->format("Ymd"), $this->closedDays) !== false) {
      array_splice($this->closedDays, $k, 1);
    }

    return $this;
  }

  /**
   * Devuelve true si la fecha es un dia en que no se atiende al publico
   *
   * @param Carbon $date
   * @return bool
   */
  public function isClosed(Carbon $date)
  {
    return array_search($date->format("Ymd"), $this->closedDays) !== false;
  }

  /**
   * Devuelve la cantidad de dias laborales entre dos fechas
   *
   * @param Carbon $from
   * @param Carbon $to
   * @param bool $including
   * @param bool $checkHollyDaysCalendar
   * @return int
   */
  public function daysBetween(Carbon $from, Carbon $to, $including = true, $checkHollyDaysCalendar = true)
  {
    return $from->diffInDaysFiltered(function (Carbon $day) use ($checkHollyDaysCalendar) {
      return $this->isOpenedDay($day, $checkHollyDaysCalendar);
    }, $to->addHours($including ? 24 : 0), false);
  }

  /**
   * Devuelve la cantidad de horas entre dos dias hábiles
   *
   * @param Carbon $from
   * @param Carbon $to
   * @param bool $checkHollyDaysCalendar
   * @return int
   */
  function hoursBetween(Carbon $from, Carbon $to, $checkHollyDaysCalendar = true)
  {
    $hours = $from->diffInHoursFiltered(function (Carbon $day) use ($checkHollyDaysCalendar) {
      return $this->isOpenedDay($day, $checkHollyDaysCalendar);
    }, $to);

    return $hours;
  }

  /**
   * Resta dias hábiles y devuelve una fecha
   *
   * @param Carbon $date
   * @param int $days
   * @param bool $checkHollyDaysCalendar
   * @return Carbon
   */
  public function subDaysFrom(Carbon $date, int $days, $checkHollyDaysCalendar = true)
  {
    $resultDate = $date->copy();

    while ($days > 0) {
      if ($this->isOpenedDay($resultDate->subDay(), $checkHollyDaysCalendar)) {
        $days--;
      }
    }

    return $resultDate;
  }

  /**
   * Suma dias hábiles y devuelve la fecha
   *
   * @param Carbon $date
   * @param int $days
   * @param bool $checkHollyDaysCalendar
   * @return Carbon
   */
  public function addDaysTo(Carbon $date, int $days, $checkHollyDaysCalendar = true)
  {
    $resultDate = $date->copy();

    while ($days > 0) {
      if ($this->isOpenedDay($resultDate->addDay(), $checkHollyDaysCalendar)) {
        $days--;
      }
    }

    return $resultDate;
  }

  /**
   * Devuelve true si la fecha es un dia de atención al publico
   *
   * @param Carbon $date
   * @param bool $checkHollyDaysCalendar
   * @return bool
   */
  public function isOpenedDay(Carbon $date, $checkHollyDaysCalendar = true)
  {
    return !$this->isWeekendDay($date)
      && !$this->isHoliday($date, $checkHollyDaysCalendar)
      && !$this->isClosed($date);
  }

  /**
   * Devuelve true si la fecha es un dia en que no hábil
   *
   * @param Carbon $date
   * @param bool $checkHollyDaysCalendar
   * @return bool
   */
  public function isClosedDay(Carbon $date, $checkHollyDaysCalendar = true)
  {
    return $this->isWeekendDay($date)
      || $this->isHoliday($date, $checkHollyDaysCalendar)
      || $this->isClosed($date);
  }

  /**
   * Devuelve la fecha del día hábil siguiente
   *
   * @param Carbon $date
   * @param int $days
   * @param bool $checkHollyDaysCalendar
   * @return Carbon
   */
  public function nextBusinessDay(Carbon $date, int $days = 1, $checkHollyDaysCalendar = true): Carbon
  {
    $nextDay = $date->addDays($days);

    if ($this->isOpenedDay($nextDay, $checkHollyDaysCalendar)) {
      return $nextDay;
    }

    return $this->nextBusinessDay($nextDay);
  }

  /**
   * Devuelve la fecha del día hábil anterior
   *
   * @param Carbon $date
   * @param int $days
   * @param bool $checkHollyDaysCalendar
   * @return Carbon
   */
  public function previousBusinessDay(Carbon $date, int $days = 1, $checkHollyDaysCalendar = true): Carbon
  {
    $previousDay = $date->subDays($days);

    if ($this->isOpenedDay($previousDay, $checkHollyDaysCalendar)) {
      return $previousDay;
    }

    return $this->previousBusinessDay($previousDay);
  }
}
