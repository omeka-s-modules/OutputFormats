<?php
namespace OutputFormats;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include sprintf('%s/config/module.config.php', __DIR__);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        /**
         * Render output format selectors.
         */
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $selectors = $config['output_formats_selectors'];
        foreach ($selectors as $selector) {
            $sharedEventManager->attach(
                $selector['controller'],
                $selector['event'],
                function (Event $event) use ($selector, $services) {
                    // Check if this selector should be rendered.
                    $status = $services->get('Omeka\Status');
                    if ($status->isSiteRequest()) {
                        $addSelectorsSite = $services
                            ->get('Omeka\Settings\Site')
                            ->get('output_formats_add_selectors_site');
                        if (!$addSelectorsSite) {
                            // Do not render this selector.
                            return;
                        }
                    }
                    // Render the selector.
                    $view = $event->getTarget();
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
                // Add the element group.
                $elementGroups = $form->getOption('element_groups', []);
                $elementGroups['output_formats'] = 'Output Formats'; // @translate
                $form->setOption('element_groups', $elementGroups);
                // Add the element.
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
