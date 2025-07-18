<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Portail Education API',
                'description' => 'Documentation de l\'API pour le Portail Education',
                'version' => '1.0.0',
                'contact' => [
                    'email' => 'support@example.com'
                ],
                'license' => [
                    'name' => 'Apache 2.0',
                    'url' => 'http://www.apache.org/licenses/LICENSE-2.0.html'
                ],
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'docs' => storage_path('api-docs'),
                'views' => base_path('resources/views/vendor/l5-swagger'),
                'base' => env('L5_SWAGGER_BASE_PATH', null),
                'annotations' => [
                    base_path('app'),
                    base_path('app/Http/Controllers'),
                    base_path('Modules'),
                    base_path('Modules/Auth/app/Http/Controllers'),
                    base_path('Modules/Etudiant/app/Http/Controllers'),
                    base_path('Modules/Classes/app/Http/Controllers'),
                ],
                'excludes' => [],
            ],
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('APP_URL'),
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],
        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSET_PATH', 'vendor/swagger-api/swagger-ui'),
            'annotations' => [
                base_path('app'),
                base_path('Modules'),
                base_path('Modules/*/Http/Controllers'),
            ],
        ],
        'scan_options' => [
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => '3.0.0',
        ],
        'security' => [
            'BearerAuth' => [
                'type' => 'http',
                'description' => 'Authentification par jeton Bearer',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
            ],
        ],
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_docs' => env('L5_SWAGGER_GENERATE_YAML_DOCS', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => null,
        'validator_url' => null,
        'ui_options' => [
            'doc_expansion' => 'none',
            'filter' => true,
            'try_it_out_enabled' => true,
            'persist_authorization' => true,
            'request_duration' => true,
        ],
        'model_and_enum_properties_always_required' => env('L5_SWAGGER_MODEL_AND_ENUM_PROPERTIES_ALWAYS_REQUIRED', false),
        'post_and_put_body_parameters_location' => 'formData',
    ],
];
