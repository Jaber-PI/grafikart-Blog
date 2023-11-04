<?php
// $dir = dirname(__DIR__);

// dd($dir);
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog' ?></title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <!-- font awesome  -->
    <link rel="stylesheet" href="../../layout/css/all.min.css" />
    <link rel="stylesheet" href="../../layout/css/main.css" />

</head>


<body class="d-flex flex-column h-100 pt-3">
    <?php include 'nav.php' ?>

    <?= $content ?>

    <footer class="mt-auto p-4 bg-dark text-white">
        <?php if (defined('DEBUG_TIME')) : ?>
            <div class="debug-time container">
                this page charged in <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?> ms
            </div>
        <?php endif ?>
    </footer>
    <!-- <script src="layout/js/bootstrap.min.js"></script> -->
    <script src="../../layout/js/bootstrap.bundle.min.js"></script>
    <script src="../../layout/js/jquery-3.7.0.min.js"></script>
    <!-- <script src="layout/js/backend.js"></script> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script> -->
</body>

</html>