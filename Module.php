<?php
namespace OutputFormats;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;

class Module extends AbstractModule
{
    protected $selectors = [
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
    ];

    public function getConfig()
    {
        return include sprintf('%s/config/module.config.php', __DIR__);
    }

    public function install(ServiceLocatorInterface $services)
    {
    }

    public function uninstall(ServiceLocatorInterface $services)
    {
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
    }

    public function handleConfigForm(AbstractController $controller)
    {
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        foreach ($this->selectors as $selector) {
            $sharedEventManager->attach(
                $selector['controller'],
                $selector['event'],
                function (Event $event) use ($selector) {
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
    }
}
