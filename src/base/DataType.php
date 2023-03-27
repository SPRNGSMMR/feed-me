<?php

namespace craft\feedme\base;

use Cake\Utility\Hash;
use craft\base\Component;
use craft\helpers\UrlHelper;

/**
 *
 * @property-read mixed $name
 * @property-read mixed $class
 */
abstract class DataType extends Component
{
    // Public
    // =========================================================================

    /**
     * @return mixed
     */
    public function getName(): string
    {
        /** @phpstan-ignore-next-line */
        return static::$name;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return get_class($this);
    }

    /**
     * @param $array
     * @param $feed
     */
    public function setupPaginationUrl($array, $feed): void
    {
        if (!$feed->paginationNode) {
            return;
        }

        // Find the URL value in the feed
        $flatten = Hash::flatten($array, '/');
        $url = Hash::get($flatten, $feed->paginationNode);

        if ($feed->paginationURLPrefix && $url) {
            $url = $feed->paginationURLPrefix . $url;
        } else if ($url && UrlHelper::isRootRelativeUrl($url)) {
            // if the feed provides a root relative URL, make it whole again based on the feed.
            $url = UrlHelper::hostInfo($feed->feedUrl) . $url;
        }

        // Replace the mapping value with the actual URL
        $feed->paginationUrl = $url;
    }
}
