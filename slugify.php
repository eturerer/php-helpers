<?php 

function slugify(string $str, array $options = []): string
{
    // Varsayılan seçenekleri ayarla
    $defaults = [
        'lowercase' => true, // Küçük harfe dönüştür
        'delimiter' => '-', // Ayraç karakteri
        'limit' => null, // Metnin maksimum uzunluğu (null = sınırsız),
        'file' => false,
        'replacements' => [], // '@' ve '&' karakterlerini özel olarak değiştir
        'transliterate' => true,
    ];

    // Seçenekleri varsayılanlarla birleştir
    $options = array_merge($defaults, $options);

    // Dosya uzantısını alma
    $file_ext = '';
    if ($options['file']) {
        $file_ext = pathinfo($str, PATHINFO_EXTENSION);
        $str = pathinfo($str, PATHINFO_FILENAME);
    }

    $str =  strtr($str, $options['replacements']);

    // Transliterasyon
    if ($options['transliterate']) {
        $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII; [\u0100-\u7fff] Remove');
        $str = $transliterator->transliterate($str);
    }

    // Özel olmayan karakterleri ayraçla değiştir
    $str = preg_replace('/[^\p{L}\p{N}]+/u', $options['delimiter'], $str);

    // Birden fazla ayraçları tek bir ayraçla değiştir
    $str = preg_replace('/' . preg_quote($options['delimiter'], '/') . '+/', $options['delimiter'], $str);

    // Metni belirli bir uzunluğa kısalt
    if ($options['limit'] !== null && mb_strlen($str, 'UTF-8') > $options['limit']) {
        $str = mb_substr($str, 0, $options['limit'], 'UTF-8');
    }

    // Eğer son karakter ayraçsa, onu kaldır
    $str = trim($str, $options['delimiter']);

    // Küçük harfe dönüştür
    if ($options['lowercase']) {
        $str = mb_strtolower($str, 'UTF-8');
    }

    // Dosya uzantısını slug'a ekleme
    if ($options['file'] && $file_ext) {
        $str .= '.' . $file_ext;
    }

    return $str;
}
