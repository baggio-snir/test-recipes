<?php
    require_once './libs/librecipes.php';
    
    $recipes = getAllRecipes();
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
                <?php foreach($recipes as $recipe) { ?>
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
                </article><?php } ?>
            </section>
            <aside>
                Quelques recettes au hasard...
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
