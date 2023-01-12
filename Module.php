<?php
namespace OutputFormats;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;

class Module extends AbstractModule
{
    /**
     * @var array List of available output format selectors
     */
    protected $selectors = [
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
    ];

    public function getConfig()
    {
        return include sprintf('%s/config/module.config.php', __DIR__);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        /**
         * Render output format selectors.
         */
        foreach ($this->selectors as $selector) {
            $sharedEventManager->attach(
                $selector['controller'],
                $selector['event'],
                function (Event $event) use ($selector) {
                    // Check if this selector should be rendered.
                    $services = $this->getServiceLocator();
                    $status = $services->get('Omeka\Status');
                    if ($status->isSiteRequest()) {
                        $addSelectorsSite = $services
                            ->get('Omeka\Settings\Site')
                            ->get('output_formats_add_selectors_site');
                        if (!$addSelectorsSite) {
                            return;
                        }
                    }
                    // Render the selector.
                    $view = $event->getTarget();
                    $view->headScript()->appendFile($view->assetUrl('js/output-formats.js', 'OutputFormats'));
                    echo $view->partial('common/output-formats-format-selector', [
                        'url' => $view->url(
                            'api-local/default',
                            [
                                'resource' => $selector['resource'],
                                'id' => $view->params()->fromRoute('id'),
                            ],
                            [
                                'force_canonical' => true,
                            ]
                        ),
                        'query' => json_encode($view->params()->fromQuery(), JSON_FORCE_OBJECT),
                    ]);
                }
            );
        }
        /**
         * Add site settings.
         */
        $sharedEventManager->attach(
            'Omeka\Form\SiteSettingsForm',
            'form.add_elements',
            function (Event $event) {
                $services = $this->getServiceLocator();
                $siteSettings = $services->get('Omeka\Settings\Site');
                $form = $event->getTarget();

                $elementGroups = $form->getOption('element_groups', []);
                $elementGroups['output_formats'] = 'Output Formats'; // @translate
                $form->setOption('element_groups', $elementGroups);

                $form->add([
                    'type' => 'checkbox',
                    'name' => 'output_formats_add_selectors_site',
                    'options' => [
                        'element_group' => 'output_formats',
                        'label' => 'Add output format selector to resource pages',
                    ],
                    'attributes' => [
                        'value' => $siteSettings->get('output_formats_add_selectors_site'),
                    ],
                ]);

            }
        );
    }
}
