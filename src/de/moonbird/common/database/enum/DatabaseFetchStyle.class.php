<?php

/**
 * Enumeration used to set the wanted fetch style.
 * Translation to the db-specific fetch style is performed by the Connection class, which uses the translation
 * map located in the specific database class.
 */
abstract class DatabaseFetchStyle
{
  const FETCH_BOTH = 0;
  const FETCH_ARRAY = 1;
  const FETCH_ASSOC = 2;
}