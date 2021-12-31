<?php
require_once __DIR__.'/sql.php';

$cfg = parse_ini_file(__DIR__.'/cfg.ini', true);
// check if cfg file is ok
if(empty($cfg)) {
    throw new RuntimeException('Invalid file cfg.ini : parse error');
} elseif(!array_key_exists('sql', $cfg)) {
    throw new RuntimeException('Invalid file cfg.ini : missing sql section');
} elseif(!array_key_exists('user', $cfg['sql'])) {
    throw new RuntimeException('Invalid file cfg.ini : missing sql.user property');
} elseif(!array_key_exists('pwd', $cfg['sql'])) {
    throw new RuntimeException('Invalid file cfg.ini : missing sql.pwd property');
} elseif(!array_key_exists('database', $cfg['sql'])) {
    throw new RuntimeException('Invalid file cfg.ini : missing sql.database property');
} elseif(!array_key_exists('host', $cfg['sql'])) {
    throw new RuntimeException('Invalid file cfg.ini : missing sql.host property');
}

// all set, let's configure
SQL::configure($cfg['sql']['host'], $cfg['sql']['user'], $cfg['sql']['pwd'], $cfg['sql']['database']);
define('DBNAME', $cfg['sql']['database']);
unset($cfg);

function createRecipe(string $title): ?int {
    return SQL::getInstance()->qi(
            'insert into `recipes` (`title`, `ingredients`, `steps`, `preptime`) values(:t, "", "", null)',
            ['t' => $title,]
            );
}

function saveRecipe(int $id, string $title, string $ingredients, string $steps, int $preptime): bool {
    $r = SQL::getInstance()->q(
            'update `recipes` set `title`=:t, `ingredients`=:i, `steps`=:s, `preptime`=:p where `id`=:r',
            ['t' => $title, 'i' => $ingredients, 's' => $steps, 'p' => $preptime, 'r' => $id,]
            );
    return (1 === $r);
}

function deleteRecipe(int $id): bool {
    $r = SQL::getInstance()->q('delete from `recipes` where `id`=:id', ['id' => $id,]);
    return (0 < $r);
}

function getRecipe(int $id): ?array {
    return SQL::getInstance()->qo('select * from `recipes` where `id`=:id', ['id' => $id,]);
}

function getRecipeComments(int $recipeId): array {
    return SQL::getInstance()->qa('select * from `grades` where `recipe`=:id', ['id' => $recipeId,]);
}

function hasAlreadyComment(int $recipeId, string $userIp): bool {
    $r = SQL::getInstance()->qo('select * from `grades` where `recipe`=:id and `ip`=:ip', ['id' => $recipeId, 'ip' => $userIp,]);
    return !empty($r);
}

function addComment(int $recipeId, string $userIp, string $content, int $grade): ?int {
    return SQL::getInstance()->qi('insert ignore into `grades` '
            . ' (`recipe`, `ip`, `grade`, `comment`, `date`) '
            . ' values(:r, :p, :g, :c, now())', [
                'r' => $recipeId,
                'p' => $userIp,
                'c' => $content,
                'g' => $grade,
            ]);
}

function searchRecipesByTitle(string $title): array {
    return SQL::getInstance()->qa('select * from `recipes` where `title` like :s order by `title` limit 50', ['s' => '%'.$title.'%',]);
}

function searchRecipesByIngredients(string $ingredients): array {
    return SQL::getInstance()->qa('select * from `recipes` where `ingredients` like :s order by `title` limit 50', ['s' => '%'.$ingredients.'%',]);
}

function getAllRecipes(): array {
    return SQL::getInstance()->qa('select * from `recipes` order by `id` asc');
}

function initDb() {
    SQL::getInstance()->warmup();
    SQL::getInstance()->q(
            'create table if not exists `recipes` ('
                . '`id` int not null primary key auto_increment, '
                . '`title` varchar(300) not null, '
                . '`ingredients` text not null, '
                . '`steps` text not null, '
                . '`preptime` int default null'
            . ') engine=InnoDB default character set "utf8"'
            );
    SQL::getInstance()->q(
            'create table if not exists `grades` ('
                . '`recipe` int not null, '
                . '`ip` varchar(50) not null, '
                . '`grade` int not null, '
                . '`comment` text not null, '
                . '`date` datetime not null, '
                . 'primary key(`recipe`, `ip`), '
                . 'constraint `fk_grades_recipe` foreign key (`recipe`) references `recipes`(`id`) on delete cascade '
            . ') engine=InnoDB default character set "utf8"'
            );
}

function destroyDb(string $db = null) {
    if(empty($db)) { $db = DBNAME; }
    SQL::getInstance()->q('drop database if exists `'.$db.'`');
}

// automatically load database
initDb();
