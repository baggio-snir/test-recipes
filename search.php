<?php
    require_once './libs/librecipes.php';
    
    $recipes = [];
    
    if(!empty($_REQUEST['searchByTitle'])) {
        $recipes = searchRecipesByTitle($_REQUEST['qt']);
    } elseif(!empty($_REQUEST['searchByIngredients'])) {
        $recipes = searchRecipesByTitle($_REQUEST['qi']);
    }
?><!DOCTYPE html>
<html>
    <head>
        <title>Le Cuisinotron</title>
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
                        <a href="index.php">Recettes en vrac</a>
                    </li>
                    <li>
                        <a href="search.php">Rechercher</a>
                    </li>
                    <li>
                        <a href="admin">Administration</a>
                    </li>
                </menu>
            </nav>
        </header>
        <main>
            <section>
                <?php if(!empty($recipes)) {
                    foreach($recipes as $recipe) { ?>
                <article>
                    <header>
                        <h2>
                            <a href="recipe.php?rid=<?php echo $recipe['id']; ?>">
                                <?php echo htmlspecialchars($recipe['title']); ?>
                            </a>
                        </h2>
                        <p>
                            ⏱
                            <?php
                            if(!empty($recipe['preptime'])) {
                                echo $recipe['preptime'].' min.';
                            } else { echo 'Instantané'; }
                            ?>
                        </p>
                    </header>
                    <p>
                        <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?>
                    </p>
                </article><?php }
                } else { ?>
                <article>
                    <p>
                        Aucune recette trouvée pour cette recherche.
                    </p>
                </article>
                <?php } ?>
            </section>
            <aside>
                <form method="post">
                    <fieldset>
                        <legend>
                            Recherche par titre
                        </legend>
                        <p>
                            <input type="search"
                                   name="qt"
                                   required="required"<?php if(!empty($_REQUEST['qt'])) { ?>
                                   value="<?php echo htmlspecialchars($_REQUEST['qt']); ?>"<?php } ?>
                                   placeholder="Titre de la recette" />
                            <input type="submit" name="searchByTitle" value="Rechercher par ce titre" />
                        </p>
                    </fieldset>
                </form>
                <form method="post">
                    <fieldset>
                        <legend>
                            Recherche par ingrédient
                        </legend>
                        <p>
                            <input type="search"
                                   name="qi"
                                   required="required"<?php if(!empty($_REQUEST['qi'])) { ?>
                                   value="<?php echo htmlspecialchars($_REQUEST['qi']); ?>"<?php } ?>
                                   placeholder="Un des ingrédients de la recette" />
                            <input type="submit" name="searchByIngredients" value="Rechercher par cet ingrédient" />
                        </p>
                    </fieldset>
                </form>
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
