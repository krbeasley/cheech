<?php

/** /app/Providers/config
 *
 * Provides a global function for searching through site configuration files.
 */

declare(strict_types=1);

/** Recursively search through arrays for a given value string. Used mainly by the config() function.
 *
 * @param array $contents          -- The 'haystack'
 * @param array $searchElements    -- The 'needle'
 * @return string|int|array
 * @throws Exception
 */
function recursiveArraySearch(array $contents, array $searchElements) : mixed {
    // get the end of the array to find what the user is ultimately looking for
    $finalSearchElement = $searchElements[array_key_last($searchElements)];

    // return if the element exists at the current level
    if (array_key_exists($finalSearchElement, $contents)) {
        return $contents[$finalSearchElement];
    }
    else {
        try {
            // reset the contents array to the nested array found with the first element in the searchElements
            $contents = $contents[$searchElements[0]];
        }
        catch (\Exception) {
            throw new Exception("Error while searching for '{$searchElements[0]}' element. Please ensure that config element exists.");
        }
        // drop the first element from the search elements for the next go around
        array_shift($searchElements);

        // rerun it
        return recursiveArraySearch($contents, $searchElements);
    }
}

/** Gets a specified config value from the config directory.
 *
*  @param string $configPath   -- Dot separated string where dots signify a 'deeper' search.
*  @return string|int|array    -- recursiveArraySearch()
 * @throws Exception
 *
 *  Example:
 *  'app.name'          -- Returns the value of 'name' inside config/app.php
 *  'my.config.item'    -- Returns the value of 'item' from within the 'config' array inside config/my.php
 *
 *  Note: Each config file should be located within the config/ directory, and should return a single
 *  associative array. This array can be as nested as you need it to be.
 */
function config(string $configPath) : mixed {
    $searchElements = explode('.', $configPath);
    $configDirectoryPath = dirname(__DIR__) 
        . DIRECTORY_SEPARATOR . 'config'; // project root dir

    // error if config directory doesn't exist
    if (!$configFiles = scandir($configDirectoryPath)) {
        throw new \Exception('Config files not found');
    }
    // error if config file cannot be found
    else if (!in_array("$searchElements[0].php", $configFiles)) {
        throw new \Exception('Config file not found');
    }
    // error if config path does not have a specified config element to return
    else if (count($searchElements) < 2) {
        throw new \Exception('Please supply a config element to return');
    }

    // get the initial array
    $contents = include dirname(__DIR__) . '/config/' . $searchElements[0] . '.php';
    // strip the first element because that's the filename. We don't need that after this step
    array_shift($searchElements);

    return recursiveArraySearch($contents, $searchElements);
}
