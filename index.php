<?php
require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Paginator\Paginator;
use Source\Models\Post;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Artigos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <?php
        $post = new Post();

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        $paginator = new Paginator("http://localhost/luizpaulogroup/luizpaulogroup/001/?page=", "Página", array("Primeira página", "Primeira"), array("Última página", "Última"));

        $paginator->pager($post->find()->count(), 3, $page, 5);

        $posts = $post->find()->limit($paginator->limit())->offset($paginator->offset())->fetch(true);
        ?>
        <p>Página <?php echo $paginator->page(); ?> de <?php echo $paginator->pages(); ?></p>
        <?php
        if ($posts) {

            foreach ($posts as $post) {
                ?>
                <article class="post">
                    <img src="<?php echo $post->cover; ?>">
                    <div>
                        <h1><?php echo $post->title; ?></h1>
                        <div><?php echo $post->description; ?></div>
                    </div>
                </article>
                <?php
            }
        }
        ?>

        <?php echo $paginator->render(); ?>
    </body>
</html>
