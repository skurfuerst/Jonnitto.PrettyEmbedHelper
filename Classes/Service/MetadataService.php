<?php

namespace Jonnitto\PrettyEmbedHelper\Service;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Jonnitto\PrettyEmbedHelper\Service\YoutubeService;
use Jonnitto\PrettyEmbedHelper\Service\VimeoService;

/**
 * @Flow\Scope("singleton")
 */
class MetadataService
{
    /**
     * @Flow\Inject
     * @var YoutubeService
     */
    protected $youtubeService;

    /**
     * @Flow\Inject
     * @var VimeoService
     */
    protected $vimeoService;

    /**
     * @var array
     */
    protected $defaultReturn = ['node' => null];


    /**
     * @param NodeInterface $node
     * @return array Informations about the node
     */
    public function createDataFromService(NodeInterface $node, bool $remove = false): array
    {
        if ($node->hasProperty('videoID')) {
            return $this->dataFromService($node, $remove);
        }
        return $this->defaultReturn;
    }

    /**
     * @param NodeInterface $node
     * @param string $propertyName
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return array Informations about the node
     */
    public function updateDataFromService(
        NodeInterface $node,
        string $propertyName,
        $oldValue,
        $newValue
    ): array {
        if ($propertyName === 'videoID' & $oldValue !== $newValue) {
            return $this->dataFromService($node);
        } else if ($propertyName === 'type' && $node->hasProperty('videoID')) {
            return $this->dataFromService($node);
        }
        return $this->defaultReturn;
    }

    /**
     * @param NodeInterface $node
     * @param boolean $remove 
     * @return array Informations about the node
     */
    protected function dataFromService(NodeInterface $node, bool $remove = false): array
    {
        $youtube = $this->youtubeService->getAndSaveDataFromOembed($node, $remove);

        if (isset($youtube)) {
            return $youtube;
        }

        $vimeo = $this->vimeoService->getAndSaveDataFromOembed($node, $remove);

        if (isset($vimeo)) {
            return $vimeo;
        }

        return $this->defaultReturn;
    }
}
