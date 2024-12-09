<?php 

function generate_tracking_link($tracking_no, $company)
{
    switch ($company) {
        case 'PTT':
            return "https://gonderitakip.ptt.gov.tr/Track/Verify?q={$tracking_no}";
        case 'YK':
            return "https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code={$tracking_no}";
        case 'SRT':
            return "https://www.suratkargo.com.tr/KargoTakip/?kargotakipno={$tracking_no}";
        case 'ARK':
            return "http://kargotakip.araskargo.com.tr/mainpage.aspx?code={$tracking_no}";
        case 'MNG':
            return "https://kargotakip.mngkargo.com.tr/?takipNo={$tracking_no}";
        case 'UPS':
            return "https://www.ups.com.tr/WaybillSorgu.aspx?Waybill={$tracking_no}";
        case 'DHL':
            return "https://www.dhl.com.tr/exp-tr/express/tracking.html?AWB={$tracking_no}&brand=DHL";

        default:
            return "Geçersiz kargo şirketi";
    }
}
