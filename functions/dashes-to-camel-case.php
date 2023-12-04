<?php
/**
 * Converts a string-with-dashes to camelCase.
 * @param string $string The string to convert
 * @param bool $capitalizeFirstCharacter Set to true to capitalize the first character, otherwise the first character will be lowercase.
 * @return string The converted string
 */
function dashes_to_camel_case(string $string, bool $capitalizeFirstCharacter = false): string
{
  $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

  if (!$capitalizeFirstCharacter) {
    $str[0] = strtolower($str[0]);
  }

  return $str;
}