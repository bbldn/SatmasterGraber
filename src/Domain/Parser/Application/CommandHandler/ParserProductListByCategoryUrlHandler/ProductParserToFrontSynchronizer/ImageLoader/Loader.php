<?php

namespace App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ImageLoader;

use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\ProductParserToFrontSynchronizer\ImageLoader\ImageProvider\Provider as ImageProvider;

class Loader
{
    private ImageProvider $imageProvider;

    /**
     * @param ImageProvider $imageProvider
     */
    public function __construct(ImageProvider $imageProvider)
    {
        $this->imageProvider = $imageProvider;
    }

    /**
     * @param string $url
     * @return void
     */
    private function save(string $url): void
    {
        $name = basename($url);
        $response = @file_get_contents("https://satmaster.kiev.ua$url");
        if (false !== $response) {
            $path = $this->imageProvider->getImagePath() . $name;
            file_put_contents($path, $response);
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public function loadByURL(string $url): string
    {
        $path = $this->imageProvider->getImageUrl() . basename($url);
        if (false === file_exists($path)) {
            $this->save($url);
        }

        return $path;
    }
}