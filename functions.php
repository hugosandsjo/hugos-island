<?php

// insert error messages to the $errors array if information is missing
function validateField(string $field, string $message) // function to see if field is empty
{
                    global $errors;
                    if ($field === '') {
                                        $errors[] = $message . '<br>';
                    }
}
