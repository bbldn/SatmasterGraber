<?php

namespace App\Context\Parser\Application\CommandHandler;

use App\Context\Parser\Domain\ValueObject\URL;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Context\Parser\Application\Command\DownloadProductsPicturesByCategoryURL;
use App\Context\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Context\Parser\Application\Command\DownloadProductsPicturesByCategoryURLHandler as Base;

class DownloadProductsPicturesByCategoryURLHandler implements Base
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
     * @param DownloadProductsPicturesByCategoryURL $command
     * @return void
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    public function __invoke(DownloadProductsPicturesByCategoryURL $command): void
    {
        $onInit = $command->getOnInit();
        $onStep = $command->getOnStep();
        $urls = $this->categoryParser->parse(new URL($command->getUrl()));
        if (null !== $onInit) {
            $onInit(count($urls));
        }

        foreach ($urls as $url) {
            $product = $this->productParser->parse(new URL($url));

            $images = $product->getImages() ?? [];
            foreach ($images as $image) {
                $array = pathinfo($image);

                $url = "https://satmaster.kiev.ua$image";
                $response = $this->httpClient->request('GET', $url);
                $fileName = "{$this->kernel->getProjectDir()}/var/images/{$array['basename']}";
                file_put_contents($fileName, $response->getContent(false));
            }

            if (null !== $onStep) {
                $onStep();
            }
        }
    }
}