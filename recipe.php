<?php
    require_once './libs/librecipes.php';
    
    $recipe = null;
    if(!empty($_REQUEST['rid'])) {
        $recipe = getRecipe($_REQUEST['rid']);
    } // no rid provided : error
    
    if(empty($recipe)) { // no rid or no recipe found => just display an error, the dirty way
        http_response_code(404);
        exit;
    }
    // if we're here, $recipe HAS to be filled
    
?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo htmlspecialchars($recipe['title']); ?> - Le Cuisinotron</title>
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
                <article>
                    <header>
                        <h2>
                            <?php echo htmlspecialchars($recipe['title']); ?>
                        </h2>
                        <p>
                            Temps de préparation:
                            <?php
                            if(!empty($recipe['preptime'])) {
                                echo $recipe['preptime'].' min.';
                            } else { echo 'Instantané'; }
                            ?>
                        </p>
                    </header>
                    <p>
                        <?php echo nl2br(htmlspecialchars($recipe['steps'])); ?>
                    </p>
                </article>
            </section>
            <aside>
                <h2>Ingrédients</h2>
                <p>
                    <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?>
                </p>
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
