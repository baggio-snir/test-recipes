<?php
require_once './libs/librecipes.php';

// swap to test DB
define('TESTDB', DBNAME.'_test');
SQL::getInstance()->redefineDbname(TESTDB);

// clean up and reset
destroyDb(TESTDB);
initDb();

// start tests
$rid = createRecipe('Test');
assert('!empty($rid)', 'Create recipe failed');

$recipe = getRecipe($rid);
assert('$recipe["title"]=="Test"', 'Recipe title did not saved');
assert('empty($recipe["ingredients"]) && empty($recipe["steps"]) && empty($recipe["preptime"])', 'Recipe data should be empty');

$r = saveRecipe($rid, 'Test 2', 'Salade, Tomates, Oignons', '– Écrasez les gambas
– Faites bouillir les pois chiches
– Incorporez les gambas dans les pois chiches
– Faites cuire à 240° pendant 20 minutes
– À déguster avec un bon vin blanc', 120);
assert('$r', 'Recipe was not updated');

$recipe = getRecipe($rid);
assert('!empty($recipe["ingredients"])', 'Recipe ingredients should be saved');
assert('!empty($recipe["steps"])', 'Recipe steps should be saved');
assert('!empty($recipe["preptime"]) && (120 == $recipe["preptime"])', 'Recipe preptime should be saved as 120m');

$comments = getRecipeComments($rid);
assert('empty($comments)', 'Recipe should have no comment yet');

$ac = hasAlreadyComment($rid, '::1');
assert('!$ac', 'User should have no comment yet');

$added = addComment($rid, '::1', 'Test comment', 15);
assert('!empty($added)', 'addComment should not fail');

$ac = hasAlreadyComment($rid, '::1');
assert('$ac', 'User should have already comment');

$comments = getRecipeComments($rid);
assert('1 === count($comments)', 'Recipe should have exactly one comment');

$ac = hasAlreadyComment($rid, '::2');
assert('$ac', 'Other user should have not comment yet');

$ac = hasAlreadyComment(1 + $rid, '::1');
assert('$ac', 'User should have not comment on another recipe yet');

$sr = searchRecipesByTitle('test');
assert('1 === count($sr)', 'Recipe should be found with title "test"');

$sr = searchRecipesByTitle('toto');
assert('0 === count($sr)', 'Recipe should NOT be found with title "toto"');

$sr = searchRecipesByIngredients('tomate');
assert('1 === count($sr)', 'Recipe should be found with ingredients "tomate"');

$sr = searchRecipesByIngredients('test');
assert('0 === count($sr)', 'Recipe should NOT be found with ingredients "test"');

$sr = getAllRecipes();
assert('1 === count($sr)', 'We should have only one recipe');

$d = deleteRecipe($rid);
assert('$d', 'Delete should not fail');
$recipe = getRecipe($rid);
assert('empty($recipe)', 'Recipe should not be found anymore');
$comments = getRecipeComments($rid);
assert('empty($comments)', 'Recipe should have no comment');
$sr = getAllRecipes();
assert('empty($sr)', 'We should have no recipe');

// clean up
destroyDb(TESTDB);
