<?php
namespace OutputFormats\ViewHelper;

use Laminas\View\Helper\AbstractHelper;

class OutputFormatsSelector extends AbstractHelper
{
    /**
     * Render an output format selector.
     *
     * @param string $resource The resource name
     * @param int|string|null $id The resource ID for a get request, null is getList request
     * @param array|null $query The resource query
     */
    public function __invoke(string $resource, $id, array $query)
    {
        $view = $this->getView();
        return $view->partial('common/output-formats-selector', [
            // Use the api-local endpoint to use the current cookie-based session
            // instead of the key_identity and key_credential parameters.
            'url' => $view->url(
                'api-local/default',
                ['resource' => $resource, 'id' => $id],
                ['force_canonical' => true]
            ),
            'query' => json_encode($query, JSON_FORCE_OBJECT),
        ]);
    }
}
