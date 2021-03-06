<?php

namespace Jonnitto\PrettyEmbedHelper\Service;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class OembedService
{
    /**
     * Grab the data of a publicly embeddable video hosted on youtube
     * @param string|integer $id The "id" of a video
     * @return mixed The data or null if there's an error
     */
    public static function youtube($id, $type = 'video')
    {
        if (!$id || ($type !== 'video' && $type !== 'playlist')) {
            return null;
        }
        $pathAndQuery = $type === 'video' ? "watch?v={$id}" : "playlist?list={$id}";
        $url = urlencode("https://youtube.com/{$pathAndQuery}");
        $data = json_decode(@file_get_contents("https://www.youtube.com/oembed?url={$url}"));

        return $data ?? null;
    }

    /**
     * Grab the data of a publicly embeddable video hosted on vimeo
     * @param string|integer $id The "id" of a video
     * @return mixed The data or null if there's an error
     */
    public static function vimeo($id)
    {
        if (!$id) {
            return null;
        }

        $url = urlencode("https://vimeo.com/{$id}");
        $data = json_decode(@file_get_contents("https://vimeo.com/api/oembed.json?url={$url}&width=2560"));

        return $data ?? null;
    }

    /**
     * Remove the prototcol from a url and replace it with `//`
     *
     * @param string $url
     * @return mixed
     */
    public static function removeProtocolFromUrl(?string $url = null)
    {
        if (!is_string($url)) {
            return null;
        }
        return preg_replace('/https?:\/\//i', '//', $url);
    }
}
