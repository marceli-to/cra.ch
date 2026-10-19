<?php
namespace App\Support;

/**
 * Texts for meta descriptions and Open Graph
 */
class Seo
{
  /**
   * Plain text from the editor's HTML: tags stripped, entities decoded
   * (the old TinyMCE stored umlauts as &uuml;), cut after $limit
   * characters at a word boundary
   */
  public static function description(?string $html, int $limit = 160): string
  {
    $text = html_entity_decode(strip_tags(str_replace(['<br', '</p>'], [' <br', ' </p>'], (string) $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));

    if (mb_strlen($text) <= $limit)
    {
      return $text;
    }

    $cut = mb_substr($text, 0, $limit);
    $space = mb_strrpos($cut, ' ');

    return rtrim($space ? mb_substr($cut, 0, $space) : $cut, ' ,;:.–-') . ' …';
  }
}
