<?php

use App\Auth\Authenticator;

?>
<!-- nav bar for all pages  -->

<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand d-inline-block" href="/">ESSABER</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse ms-auto navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav w-100">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="/blog/category">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/blog/post">Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>

                <li class="nav-item ms-auto d-flex align-items-center">
                    <?php if (Authenticator::isLogged()) : ?>
                        <form action="/logout" class="ms-auto" method="POST">
                            <button type="submit" class=" btn btn-sm btn-danger">Log Out</button>
                        </form>
                    <?php else : ?>
                        <a class=" btn btn-sm btn-primary" href="/login">Log In</a>
                    <?php endif ?>
                </li>
                <li class="nav-item dropdown  ">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo '<li><a class="dropdown-item" href="members.php?do=Edit">Profile</a></li>';
                        }
                        ?>
                        <li>
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo '<a class="dropdown-item" href="logout.php">Log Out</a>';
                            } else {
                                echo '<a class="dropdown-item" href="index.php">Log In</a>';
                            }
                            ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>