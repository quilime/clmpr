<?php

    $code = isset($_GET['error']) ? $_GET['error'] : '404';

    echo '<br/>' . $code;

    exit;