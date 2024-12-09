<?php 

function identify_gtin_format(string $identifier): string
{

    if (!ctype_digit($identifier)) {
        return 'MPN'; // Geçersiz durumda false döndür
    }

    // GTIN numarasının yalnızca rakamlardan oluştuğunu kontrol et
    $length = strlen($identifier);

    return match ($length) {
        8 => 'EAN-8',
        12 => 'UPC',
        13 => match (true) {
            str_starts_with($identifier, '978') || str_starts_with($identifier, '979') => 'ISBN',
            str_starts_with($identifier, '45') || str_starts_with($identifier, '49') => 'JAN',
            default => 'EAN-13',
        },
        14 => 'EAN-14',
        default => 'MPN', // Geçersiz uzunluk durumunda MPN döndür
    };
}
