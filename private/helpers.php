<?php

function redirect($url)
{
    header("Location: $url");
    exit;
}

function escape($string)
{
    return htmlspecialchars($string);
}
