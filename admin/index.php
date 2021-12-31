<?php
    require_once './libs/librecipes.php';
    
    $loadedRecipe = null;
    
    // delete a recipe
    if(!empty($_REQUEST['deleteRecipe'])) {
        deleteRecipe($_REQUEST['deleteRecipe']);
    }
    
    // create new recipe
    if(!empty($_REQUEST['createRecipe']) && !empty($_REQUEST['title'])) {
        $rid = createRecipe($_REQUEST['title']);
        // load it
        $loadedRecipe = getRecipe($rid);
    }
    
    // load an existing recipe to update it
    if(!empty($_REQUEST['rid'])) {
        $loadedRecipe = getRecipe(intval($_REQUEST['rid']));
    }
    
    // we ask to update a recipe
    if(!empty($loadedRecipe) && !empty($_REQUEST['updateRecipe'])) {
        saveRecipe($loadedRecipe['id'], $_REQUEST['title'], $_REQUEST['ingredients'], $_REQUEST['steps'], $_REQUEST['time']);
        // reload it
        $loadedRecipe = getRecipe($loadedRecipe['id']);
    }
    
    $recipes = getAllRecipes();
?><!DOCTYPE html>
<html>
    <head>
        <title>[ADMIN] Le Cuisinotron</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="resources/basic.css" />
    </head>
    <body>
        <header>
            <h1>
                Le Cuisinotron
            </h1>
            <nav>
                <menu>
                    <li>
                        <a href="/admin/index.php">Administration</a>
                    </li>
                    <li>
                        <a href="../index.php">Retour au site</a>
                    </li>
                </menu>
            </nav>
        </header>
        <main>
            <section>
                <article>
                    <table>
                        <caption>
                            Liste des recettes
                        </caption>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>⏱</th>
                                <th>Ingredients ?</th>
                                <th>Etapes ?</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recipes as $recipe) { ?>
                            <tr>
                                <td><?php echo $recipe['id']; ?></td>
                                <th>
                                    <a href="./index.php?rid=<?php echo $recipe['id']; ?>">
                                        <?php echo htmlspecialchars($recipe['title']); ?>
                                    </a>
                                </th>
                                <td>
                                    <?php echo $recipe['preptime']; ?>
                                </td>
                                <td>
                                    <?php echo empty($recipe['ingredients'])? 'n':'Y'; ?>
                                </td>
                                <td>
                                    <?php echo empty($recipe['steps'])? 'n':'Y'; ?>
                                </td>
                                <td>
                                    <a href="./index.php?rid=<?php echo $recipe['id']; ?>">✏</a>
                                    <a href="./index.php?deleteRecipe=<?php echo $recipe['id']; ?>"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette recette ?');">❌</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </article>
            </section>
            <aside>
                <?php if(empty($loadedRecipe)) { ?>
                <form method="post" action="./index.php">
                    <fieldset>
                        <legend>Créer une nouvelle recette</legend>
                        <p>
                            <label>
                                Titre de la recette:
                                <input type="text" name="title" required="required" placeholder="Le nom de la recette" />
                            </label>
                            <input type="submit" name="createRecipe" value="Créer cette nouvelle recette !" />
                        </p>
                    </fieldset>
                </form><?php } else { ?>
                <form method="post" action="./index.php?rid=<?php echo $loadedRecipe['id']; ?>">
                    <fieldset>
                        <legend>Mise à jour de la recette #<?php echo $loadedRecipe['id']; ?></legend>
                        <p>
                            <label>
                                Titre:
                                <input type="text"
                                       name="title"
                                       required="required"
                                       placeholder="Le nom de la recette"
                                       value="<?php echo htmlspecialchars($loadedRecipe['title']); ?>" />
                            </label>
                        </p>
                        <p>
                            <label>
                                Temps de préparation:
                                <input type="number"
                                       name="time"
                                       min="0"
                                       step="5"
                                       required="required"
                                       value="<?php echo intval($loadedRecipe['preptime']); ?>" />
                            </label>
                        </p>
                        <p>
                            <label for="recipeIngredients">
                                Ingrédients:
                            </label>
                            <textarea id="recipeIngredients" name="ingredients" required="required"><?php echo htmlspecialchars($loadedRecipe['ingredients']); ?></textarea>
                        </p>
                        <p>
                            <label for="recipeSteps">
                                Etapes de préparation:
                            </label>
                            <textarea id="recipeSteps" name="steps" required="required"><?php echo htmlspecialchars($loadedRecipe['steps']); ?></textarea>
                        </p>
                        <p>
                            <input type="submit" name="updateRecipe" value="Mettre à jour cette recette !" />
                        </p>
                    </fieldset>
                </form>
                <?php } ?>
            </aside>
        </main>
        <footer>
            Copyleft Baggio SNIR
            -
            Inspiré de
            <a href="https://twitter.com/LeCuisinotron">https://twitter.com/LeCuisinotron</a>
        </footer>
    </body>
</html>
