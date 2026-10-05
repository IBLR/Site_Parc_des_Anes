<?php

return [

    'site_name' => 'Parc des Anes',

    
    // ==================================================
    // Default metadata
    // ==================================================

    'defaults' => [
        'title' => 'Parc des Anes',
        'description' =>
            'Découvrez le Parc des Anes, ses ânes, ses activités et ses actions. '
            . 'Retrouvez également notre boutique et les moyens de soutenir le parc.',
        'robots' => 'index, follow',
        'author' => 'Isabelle BAILLY LE ROCH',
    ],



    // ==================================================
    // Public pages
    // ==================================================

    // --------------------------------------------------
    // Home
    // --------------------------------------------------

    'home' => [
        'index' => [
            'title' => 'Accueil',
            'description' =>
                'Bienvenue au Parc des Anes : venez découvrir nos ânes, le parc, '
                . 'nos activités et les actions que nous menons au quotidien.',
            'robots' => 'index, follow',
        ],
    ],



    // --------------------------------------------------
    // Our donkeys
    // --------------------------------------------------

    'donkeys' => [
        'index' => [
            'title' => 'Nos ânes',
            'description' =>
                'Découvrez l\'histoire les ânes du Parc des Anes, les doyens, '
                . 'les nouveau-nés et notre mascotte.',
            'robots' => 'index, follow',
        ],
    ],



    // --------------------------------------------------
    // Access & Contact
    // --------------------------------------------------

    'contact' => [
        'index' => [
            'title' => 'Accès & Contact',
            'description' =>
                'Retrouvez toutes les informations pour venir au Parc des Anes, '
                . 'nos horaires, tarifs et moyens de contact.',
            'robots' => 'index, follow',
        ],
    ],



    // --------------------------------------------------
    // Make a donation
    // --------------------------------------------------

    'donation' => [
        'index' => [
            'title' => 'Faire un don',
            'description' =>
                'Soutenez le Parc des Anes en faisant un don et contribuez aux soins, '
                . 'au bien-être et aux actions menées pour nos ânes.',
            'robots' => 'index, follow',
        ],

        'checkout' => [
            'title' => 'Finaliser mon don',
            'description' => 'Finalisez votre don au Parc des Anes.',
            'robots' => 'noindex, nofollow',
        ],
    ],



    // --------------------------------------------------
    // Shop
    // --------------------------------------------------

    'shop' => [
        'index' => [
            'title' => 'Boutique',
            'description' =>
                'Découvrez la boutique du Parc des Anes et retrouvez nos produits '
                . 'disponibles à la vente pour soutenir le parc.',
            'robots' => 'index, follow',
        ],
    ],



    // --------------------------------------------------
    // Authentication
    // --------------------------------------------------

    'auth' => [

        'login' => [
            'index' => [
                'title' => 'Connexion',
                'description' => 'Connectez-vous à votre compte du Parc des Anes.',
                'robots' => 'noindex, nofollow',
            ],
        ],

        'register' => [
            'index' => [
                'title' => 'Inscription',
                'description' => 'Créez votre compte sur le site du Parc des Anes.',
                'robots' => 'noindex, nofollow',
            ],
        ],
    ],



    // --------------------------------------------------
    // User account
    // --------------------------------------------------

    'user' => [

        'index' => [
            'title' => 'Mon compte',
            'description' =>
                'Consultez votre compte, et vos historiques de commandes et de dons '
                . 'sur le site du Parc des Anes.',
            'robots' => 'noindex, nofollow',
        ],
    ],



    // --------------------------------------------------
    // Cart / Order
    // --------------------------------------------------

    'cart' => [

        'index' => [
            'title' => 'Panier',
            'description' =>
                'Consultez votre panier et préparez votre commande dans la boutique '
                . 'du Parc des Anes.',
            'robots' => 'noindex, nofollow',
        ],
    ],



    // --------------------------------------------------
    // Legal pages
    // --------------------------------------------------

    'legal' => [

        'legal-notice' => [
            'title' => 'Mentions légales',
            'description' => 'Consultez les mentions légales du site du Parc des Anes.',
            'robots' => 'index, follow',
        ],

        'privacy' => [
            'title' => 'Politique de confidentialité',
            'description' =>
                'Consultez la politique de confidentialité et les informations '
                . 'relatives à la protection de vos données personnelles.',
            'robots' => 'index, follow',
        ],

        'terms' => [
            'title' => 'Conditions générales de vente',
            'description' =>
                'Consultez les conditions générales de vente du Parc des Anes.',
            'robots' => 'index, follow',
        ],
    ],



    // ==================================================
    // Administration
    // ==================================================

    'admin' => [

        'index' => [
            'title' => 'Administration',
            'description' => '',
            'robots' => 'noindex, nofollow',
        ],

        'products' => [
            'index' => [
                'title' => 'Gestion des produits',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],
        ],

        'categories' => [
            'index' => [
                'title' => 'Gestion des catégories',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],
        ],

        'contacts' => [
            'index' => [
                'title' => 'Suivi des messages',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],

            'show' => [
                'title' => 'Détail du message',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],
        ],

        'donations' => [
            'index' => [
                'title' => 'Suivi des dons',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],

            'show' => [
                'title' => 'Détail du don',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],
        ],

        'orders' => [
            'index' => [
                'title' => 'Suivi des commandes',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],

            'show' => [
                'title' => 'Détail de la commande',
                'description' => '',
                'robots' => 'noindex, nofollow',
            ],
        ],
    ],



    // ==================================================
    // System
    // ==================================================

    'system' => [

        'under_construction' => [
            'title' => 'Page en construction',
            'description' =>
                'Cette page du Parc des Anes est actuellement en construction.',
            'robots' => 'noindex, nofollow',
        ],
    ],
];