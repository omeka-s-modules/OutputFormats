<?php
namespace OutputFormats;

return [
    'output_formats_selectors' => [
        // Admin selectors
        [
            'resource' => 'items',
            'controller' => 'Omeka\Controller\Admin\Item',
            'event' => 'view.browse.after',
        ],
        [
            'resource' => 'items',
            'controller' => 'Omeka\Controller\Admin\Item',
            'event' => 'view.show.after',
        ],
        [
            'resource' => 'item_sets',
            'controller' => 'Omeka\Controller\Admin\ItemSet',
            'event' => 'view.browse.after',
        ],
        [
            'resource' => 'item_sets',
            'controller' => 'Omeka\Controller\Admin\ItemSet',
            'event' => 'view.show.after',
        ],
        [
            'resource' => 'media',
            'controller' => 'Omeka\Controller\Admin\Media',
            'event' => 'view.browse.after',
        ],
        [
            'resource' => 'media',
            'controller' => 'Omeka\Controller\Admin\Media',
            'event' => 'view.show.after',
        ],
        // Site selectors
        [
            'resource' => 'items',
            'controller' => 'Omeka\Controller\Site\Item',
            'event' => 'view.browse.after',
        ],
        [
            'resource' => 'items',
            'controller' => 'Omeka\Controller\Site\Item',
            'event' => 'view.show.after',
        ],
        [
            'resource' => 'item_sets',
            'controller' => 'Omeka\Controller\Site\ItemSet',
            'event' => 'view.browse.after',
        ],
        [
            'resource' => 'item_sets',
            'controller' => 'Omeka\Controller\Site\ItemSet',
            'event' => 'view.show.after',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => sprintf('%s/../language', __DIR__),
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            sprintf('%s/../view', __DIR__),
        ],
    ],
];
