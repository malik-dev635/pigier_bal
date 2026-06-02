<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Protection contre les inscriptions multiples
    |--------------------------------------------------------------------------
    |
    | block_by_device : bloque une 2e inscription depuis le même navigateur
    |                   (cookie persistant). Fiable par appareil, contournable
    |                   en vidant les cookies ou en navigation privée.
    |
    | block_by_ip     : bloque une 2e inscription depuis la même adresse IP.
    |                   ⚠ ATTENTION : sur un Wi-Fi partagé (école, salle de bal),
    |                   tous les appareils sortent souvent sur la MÊME IP publique.
    |                   Activé, seul le PREMIER inscrit du réseau pourra créer un
    |                   compte. À n'utiliser que si chaque votant a sa propre
    |                   connexion (data mobile). Désactivé par défaut.
    |
    */

    'block_by_device' => env('REGISTRATION_BLOCK_DEVICE', true),

    'block_by_ip' => env('REGISTRATION_BLOCK_IP', false),

    // Durée de vie du cookie d'appareil (en minutes). 1 an par défaut.
    'device_cookie_minutes' => 525600,

    'device_cookie_name' => 'pea_device',

];
