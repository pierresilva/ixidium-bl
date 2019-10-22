<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

/**
 * Class to helps to create unique slugs
 *
 * @author Pierre Silva <pierremichelsilva@gmail.com>
 */
class SlugHelper
{
  public function __construct()
  {

  }
  /**
   * Get unique slug from table -> column field
   *
   * @param string $text          Text to convert to slug
   * @param string $table         Table where find unique slug
   * @param string $separator     Char to separate words
   * @param string $column        Column where find unique slug
   * @param string $connection    Configured connection to use
   *
   * @return string               Unique slug string
   */
  public static function uniqueSlug($text, $table, $separator = '-', $column = 'slug', $connection = null)
  {
    if (!$connection) {
      $connection = env('BASELINE_DB_CONNECTION');
    }
    $slug = str_slug($text, $separator);
    $slugs = self::findSlugs($slug, $table, $column, $connection);
    if(count($slugs) == 0) {
      return $slug;
    }

    return $slug . $separator . count($slugs);
  }

  /**
   * Find a slug string on dtabase table
   *
   * @param string $slug      Slug string to find
   * @param string $table     Table name
   * @param string $column    Column name
   *
   * @param $connection
   * @return Array            Array of slugs found
   */
  public static function findSlugs($slug, $table, $column, $connection = 'sqlsrv')
  {
    $conn = $connection;
    $slugs = DB::connection($conn)->table($table)
      ->where($column, 'like', $slug . '%')
      ->get()
      ->toArray();

    return $slugs;
  }
}
