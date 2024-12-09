<?php 

function time_elapsed_string(string $tm, int $rcs = 0, string $lang = 'tr'): string
{
    $cur_tm = time();
    $tm = $lang === 'tr' ? str_replace('/', '.', $tm) : str_replace(['.', '-'], '/', $tm);
    $dif = $cur_tm - strtotime($tm);

    $pds = [
        'en' => ['second', 'minute', 'hour', 'day', 'week', 'month', 'year'],
        'tr' => ['saniye', 'dakika', 'saat', 'gün', 'hafta', 'ay', 'yıl'],
        'es' => ['segundo', 'minuto', 'hora', 'día', 'semana', 'mes', 'año'],
        'fr' => ['seconde', 'minute', 'heure', 'jour', 'semaine', 'mois', 'an']
    ];

    $lngh = [1, 60, 3600, 86400, 604800, 2630880, 31570560];

    // Dil dizisinde geçerli dil olup olmadığını kontrol et
    if (!array_key_exists($lang, $pds)) {
        $lang = 'tr'; // Varsayılan dil olarak Türkçe
    }

    $v = 0;
    foreach ($lngh as $index => $length) {
        if ($dif < $length) {
            break;
        }
        $v = $index;
    }

    $no = floor($dif / $lngh[$v]);
    $suffix = $pds[$lang][$v];

    if ($no !== 1) {
        $suffix .= ($lang !== 'tr') ? 's' : '';
    }

    $result = sprintf("%d %s", $no, $suffix);

    if ($rcs === 1 && $v >= 1 && ($cur_tm - ($cur_tm - ($dif % $lngh[$v]))) > 0) {
        $result .= ' ' . time_elapsed_string(date('Y-m-d H:i:s', $cur_tm - ($dif % $lngh[$v])), 0, $lang);
    }

    // `since` terimini dil dizisine ekleyin
    $suffixTime = [
        'en' => 'ago',
        'tr' => 'önce',
        'es' => 'hace',
        'fr' => 'il y a'
        // Daha fazla dil ekleyebilirsiniz
    ];

    return $result . ' ' . ($suffixTime[$lang] ?? $suffixTime['tr']);
}
