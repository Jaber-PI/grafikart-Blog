<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

$dsn = 'mysql:host=localhost;dbname=myBlog';
$user = 'root';
$pass = '0767@mysql';
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];
try {
    $db = new PDO($dsn, $user, $pass, $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Failed to connect' . $e->getMessage();
}


$db->exec('SET FOREIGN_KEY_CHECKS = 0');
$db->exec('TRUNCATE TABLE post');
$db->exec('TRUNCATE TABLE category');
$db->exec('TRUNCATE TABLE post_category');
$db->exec('TRUNCATE TABLE user');
$db->exec('SET FOREIGN_KEY_CHECKS = 1');

$posts = [];
$categories = [];

for ($i = 0; $i < 50; $i++) {
    $name = $faker->words(4, true);
    $slug = strtolower(preg_replace(['/(.\s)/', '/(\'\s)/', '/(.\s)/'], '-', $name));
    $db->exec("INSERT INTO post SET 
                        name='{$name}', 
                        slug='{$slug}',
                        content = '{$faker->paragraphs($faker->numberBetween(7, 11), true)}',
                        created_at='{$faker->date()} {$faker->time()}'
                    ");
    $posts[] = $db->lastInsertId();
}


for ($i = 1; $i < 7; $i++) {
    $db->exec("INSERT INTO category SET 
                        name = 'category #{$i}', 
                        slug = 'category-{$i}',
                        description = '{$faker->paragraph($faker->numberBetween(3, 7))}'
                    ");
    $categories[] = $db->lastInsertId();
}


foreach ($posts as $post) {
    $random_categories  =  $faker->randomElements($categories, rand(1, 3));
    foreach ($random_categories as $cat) {
        $db->exec("INSERT INTO post_category SET
                        post_id = '{$post}',
                        category_id = '{$cat}'
                        ");
    }
}
