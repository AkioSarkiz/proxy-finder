<?php

return [

    /*
     |--------------------------------------------------------------------
     | Verify proxy
     |--------------------------------------------------------------------
     |
     | If you want get live proxy you must set true for this prop.
     | Finder will check proxy status before return.
     |
     */

    'verify' => true,


    'adapters' => [
        \AkioSarkiz\Adapters\ProxyscanAdapter::class,
        \AkioSarkiz\Adapters\FetproxylistAdapter::class,
        \AkioSarkiz\Adapters\GimmeproxyAdapter::class,
        //\AkioSarkiz\Adapters\PubproxyAdapter::class,
    ],

    /*
    |--------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------
    |
    | Section for access keys.
    |
    */

    'services' => [

        /*
         |--------------------------------------------------------------------
         | Pubproxy
         |--------------------------------------------------------------------
         |
         | website: http://pubproxy.com/
         | adapter: AkioSarkiz\Adapters\PubproxyAdapter
         |
         */

        'pubproxy' => [
            'key' => null,
        ],

    ],

    'pubproxy_key' => null,
];
