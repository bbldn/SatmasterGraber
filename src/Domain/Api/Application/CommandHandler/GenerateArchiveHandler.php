<?php

namespace App\Domain\Api\Application\CommandHandler;

use Throwable;
use ZipArchive;
use Psr\Log\LoggerInterface as Logger;
use App\Domain\Api\Domain\State\Error;
use App\Domain\Api\Domain\State\Finish;
use App\Domain\Api\Domain\State\Process;
use Symfony\Component\Filesystem\Filesystem;
use App\Domain\Parser\Domain\ValueObject\URL;
use App\Domain\Api\Domain\State\Initialization;
use App\Domain\Api\Application\Command\GenerateArchive;
use App\Domain\Common\Application\Helper\ExceptionFormatter;
use App\Domain\Api\Application\Common\State\File as StateFile;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use App\Domain\Api\Application\Command\GenerateArchiveHandler as Base;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Arguments;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class GenerateArchiveHandler implements Base
{
    private Logger $logger;

    private Filesystem $filesystem;

    private HttpClient $httpClient;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param Logger $logger
     * @param Filesystem $filesystem
     * @param HttpClient $httpClient
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        Logger $logger,
        Filesystem $filesystem,
        HttpClient $httpClient,
        ProductParser $productParser,
        CategoryParser $categoryParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->logger = $logger;
        $this->filesystem = $filesystem;
        $this->httpClient = $httpClient;
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
        $this->productToSQLGenerator = $productToSQLGenerator;
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    private function createFolders(GenerateArchive $command): void
    {
        $dumpsPath = "/tmp/graber/{$command->getUserId()}/dumps";
        if (true === is_dir($dumpsPath)) {
            $this->filesystem->remove($dumpsPath);
        }
        $this->filesystem->mkdir($dumpsPath);

        $imagesPath = "/tmp/graber/{$command->getUserId()}/images";
        if (false === is_dir($imagesPath)) {
            $this->filesystem->remove($imagesPath);
        }
        $this->filesystem->mkdir($imagesPath);
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    private function removeFolders(GenerateArchive $command): void
    {
        $this->filesystem->remove([
            "/tmp/graber/{$command->getUserId()}/dumps",
            "/tmp/graber/{$command->getUserId()}/images",
        ]);
    }

    /**
     * @param GenerateArchive $command
     * @return string
     */
    private function createArchive(GenerateArchive $command): string
    {
        $fileName = "/tmp/graber/{$command->getUserId()}.zip";
        if (true === file_exists($fileName)) {
            $this->filesystem->remove($fileName);
        }

        $zip = new ZipArchive();
        $zip->open($fileName, ZipArchive::CREATE);

        $dumpsPath = "/tmp/graber/{$command->getUserId()}/dumps";
        foreach (scandir($dumpsPath) as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("$dumpsPath/$path", "dumps/$path");
            }
        }

        $imagesPath = "/tmp/graber/{$command->getUserId()}/images";
        foreach (scandir($imagesPath) as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("$imagesPath/$path", "images/$path");
            }
        }

        $zip->close();

        return "{$command->getUserId()}.zip";
    }

    /**
     * @param GenerateArchive $command
     * @param StateFile $file
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function invoke(GenerateArchive $command, StateFile $file): void
    {
        $calculatePercent = static fn(int $current, int $total): int => (int)(($current * 100) / $total);

        $this->createFolders($command);
        $file->whiteState(new Initialization('Получаем список товаров'));
        $productsUrls = $this->categoryParser->parse(new URL($command->getSourceCategoryUrl()));
        $file->whiteState(new Initialization('Список товаров успешно получен'));

        $dumpsFileName = "/tmp/graber/{$command->getUserId()}/dumps/dumps.sql";
        file_put_contents($dumpsFileName, $this->productToSQLGenerator->sqlStartTransaction(), FILE_APPEND);
        foreach ($productsUrls as $index => $productUrl) {
            $product = $this->productParser->parse(new URL($productUrl));

            $arguments = new Arguments($product);
            $arguments->setImagePath($command->getDestinationImagesPath());
            $arguments->setCategoryId($command->getDestinationCategoryId());

            $row = $this->productToSQLGenerator->generate($arguments);
            file_put_contents($dumpsFileName, $row, FILE_APPEND);

            $images = $product->getImages() ?? [];
            foreach ($images as $image) {
                $array = pathinfo($image);

                $url = "https://satmaster.kiev.ua$image";
                $response = $this->httpClient->request('GET', $url);
                $fileName = "/tmp/graber/{$command->getUserId()}/images/{$array['basename']}";
                file_put_contents($fileName, $response->getContent(false));
            }

            $file->whiteState(new Process($calculatePercent($index + 1, count($productsUrls)), 'Обрабатываем товары'));
        }
        file_put_contents($dumpsFileName, $this->productToSQLGenerator->sqlCommit(), FILE_APPEND);

        $archivePath = $this->createArchive($command);
        $this->removeFolders($command);

        $file->whiteState(new Finish($archivePath, 'Архив успешно создан'));
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    public function __invoke(GenerateArchive $command): void
    {
        $file = new StateFile("/tmp/graber/{$command->getUserId()}.json");

        try {
            $this->invoke($command, $file);
        }  catch (Throwable $e) {
            $this->logger->error(ExceptionFormatter::e($e));
            $file->whiteState(new Error('Ошибка сервера. Обратитесь к администратору.'));
        }
    }
}