<?php
namespace App\Helpers;
use Illuminate\Support\Str;

class DateHelper
{
  public static function format($date = NULL)
  { 
    $search = [
      'sun',
      'mon',
      'tue',
      'wed',
      'thu',
      'fri',
      'sat',
    ];

    $replace = [
      'so',
      'mo',
      'di',
      'mi',
      'do',
      'fr',
      'sa'
    ];

    $date = str_replace($search, $replace, strtolower($date));
    return $date;
  }

}