<?php

namespace App\Domain\Parser\Application\CommandHandler;

use App\Domain\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Command\DownloadProductListPictureListByCategoryURL;
use App\Domain\Parser\Application\Command\DownloadProductListPictureListByCategoryURLHandler as Base;

class DownloadProductListPictureListByCategoryURLHandler implements Base
{
    private Kernel $kernel;

    private HttpClient $httpClient;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    /**
     * @param Kernel $kernel
     * @param HttpClient $httpClient
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     */
    public function __construct(
        Kernel $kernel,
        HttpClient $httpClient,
        ProductParser $productParser,
        CategoryParser $categoryParser
    )
    {
        $this->kernel = $kernel;
        $this->httpClient = $httpClient;
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
    }

    /**
     * @param DownloadProductListPictureListByCategoryURL $command
     * @return void
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function __invoke(DownloadProductListPictureListByCategoryURL $command): void
    {
        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urlList = $this->categoryParser->parse(new URL($command->getUrl()));
        if (null !== $onInit) {
            call_user_func($onInit, count($urlList));
        }

        foreach ($urlList as $url) {
            $product = $this->productParser->parse(new URL($url));

            $imageList = $product->getImages() ?? [];
            foreach ($imageList as $image) {
                $array = pathinfo($image);

                $url = "https://satmaster.kiev.ua$image";
                $response = $this->httpClient->request('GET', $url);
                $fileName = "{$this->kernel->getProjectDir()}/var/images/{$array['basename']}";
                file_put_contents($fileName, $response->getContent(false));
            }

            if (null !== $onStep) {
                call_user_func($onStep);
            }
        }
    }
}