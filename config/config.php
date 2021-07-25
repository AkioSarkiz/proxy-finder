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

    /*
     |--------------------------------------------------------------------
     | Verify host
     |--------------------------------------------------------------------
     |
     | Host for ping proxy. This can be useful if you need to check proxies for a specific host.
     | By default, host - google, it because google never down.
     |
     */

    'verify_host' => 'https://google.com',

    /*
     |--------------------------------------------------------------------
     | Proxy adapters
     |--------------------------------------------------------------------
     |
     | ProxyFinder adapters use this adapters for finding proxy.
     |
     */

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

];
