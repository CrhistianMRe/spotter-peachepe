<?php

function sanitize_string($input)
{
    return trim(htmlspecialchars($input));
}

function is_positive_integer($value)
{
    return filter_var($value, FILTER_VALIDATE_INT) !== false
        && $value > 0;
}

function is_non_negative_number($value)
{
    return is_numeric($value)
        && $value >= 0;
}

function is_valid_boolean($value)
{
    return $value === '0'
        || $value === '1'
        || $value === 0
        || $value === 1;
}

function is_not_empty($value)
{
    return trim($value) !== '';
}
