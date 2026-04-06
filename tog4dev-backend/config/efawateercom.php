<?php

return [

    /*
     * Comma-separated IPs allowed to call webhooks/callbacks (e.g. EFAWATEERCOM_ALLOW_IPS=1.2.3.4,5.6.7.8).
     */
    'allow_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('EFAWATEERCOM_ALLOW_IPS', ''))))),

];
