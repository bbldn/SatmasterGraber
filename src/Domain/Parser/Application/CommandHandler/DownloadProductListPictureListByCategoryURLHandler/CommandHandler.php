<?php

namespace App\Domain\Parser\Application\CommandHandler\DownloadProductListPictureListByCategoryURLHandler;

use App\Domain\Parser\Domain\Exception\ParseException;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Command\DownloadProductListPictureListByCategoryURL;

class CommandHandler
{
    private Kernel $kernel;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    /**
     * @param Kernel $kernel
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     */
    public function __construct(
        Kernel $kernel,
        ProductParser $productParser,
        CategoryParser $categoryParser
    )
    {
        $this->kernel = $kernel;
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
    }

    /**
     * @param string $url
     * @return string
     * @throws ParseException
     */
    private function getContent(string $url): string
    {
        $html = @file_get_contents("https://am-parts.ru$url");
        if (false === $html) {
            throw new ParseException('Error');
        }

        return $html;
    }

    /**
     * @param DownloadProductListPictureListByCategoryURL $command
     * @return void
     * @throws ParseException
     */
    public function __invoke(DownloadProductListPictureListByCategoryURL $command): void
    {
        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urlList = $this->categoryParser->parse($command->getUrl());
        if (null !== $onInit) {
            call_user_func($onInit, count($urlList));
        }

        foreach ($urlList as $url) {
            $product = $this->productParser->parse($url);

            $imageList = $product->getImages() ?? [];
            foreach ($imageList as $image) {
                $array = pathinfo($image);
                $content = $this->getContent("https://satmaster.kiev.ua$image");
                $fileName = "{$this->kernel->getProjectDir()}/var/images/{$array['basename']}";
                file_put_contents($fileName, $content);
            }

            if (null !== $onStep) {
                call_user_func($onStep);
            }
        }
    }
}