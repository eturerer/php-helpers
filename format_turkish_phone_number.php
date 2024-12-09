<?php

function format_turkish_phone_number(string $phoneNumber): string
{
    // Remove any non-numeric characters
    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

    // Remove leading international or local prefixes like '90', '0090', '+90', '0'
    $phoneNumber = preg_replace('/^(?:00|\\+)?90|^0/', '', $phoneNumber);

    // Ensure the phone number is exactly 10 digits long
    if (preg_match('/^\d{10}$/', $phoneNumber)) {
        return $phoneNumber;
    }

    // If number does not match expected format, return original input
    return $phoneNumber;
}
