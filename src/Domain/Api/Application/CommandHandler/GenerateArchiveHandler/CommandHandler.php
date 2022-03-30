<?php

namespace App\Domain\Api\Application\CommandHandler\GenerateArchiveHandler;

use Throwable;
use ZipArchive;
use Psr\Log\LoggerInterface as Logger;
use App\Domain\Api\Domain\State\Error;
use App\Domain\Api\Domain\State\Finish;
use App\Domain\Api\Domain\State\Process;
use Symfony\Component\Filesystem\Filesystem;
use App\Domain\Api\Domain\State\Initialization;
use App\Domain\Parser\Domain\Exception\ParseException;
use App\Domain\Api\Application\Command\GenerateArchive;
use App\Domain\Api\Application\Common\State\File as StateFile;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\ArgumentList;
use App\Domain\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Domain\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Domain\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class CommandHandler
{
    private Logger $logger;

    private Filesystem $filesystem;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param Logger $logger
     * @param Filesystem $filesystem
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        Logger $logger,
        Filesystem $filesystem,
        ProductParser $productParser,
        CategoryParser $categoryParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->logger = $logger;
        $this->filesystem = $filesystem;
        $this->productParser = $productParser;
        $this->categoryParser = $categoryParser;
        $this->productToSQLGenerator = $productToSQLGenerator;
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
     * @throws ParseException
     */
    private function invoke(GenerateArchive $command, StateFile $file): void
    {
        $calculatePercent = static fn(int $current, int $total): int => (int)(($current * 100) / $total);

        $this->createFolders($command);
        $file->whiteState(new Initialization('Получаем список товаров'));
        $productsUrls = $this->categoryParser->parse($command->getSourceCategoryUrl());
        $file->whiteState(new Initialization('Список товаров успешно получен'));

        $dumpsFileName = "/tmp/graber/{$command->getUserId()}/dumps/dumps.sql";
        file_put_contents($dumpsFileName, $this->productToSQLGenerator->sqlStartTransaction(), FILE_APPEND);
        foreach ($productsUrls as $index => $productUrl) {
            $product = $this->productParser->parse($productUrl);

            $arguments = new ArgumentList($product);
            $arguments->setImagePath($command->getDestinationImagesPath());
            $arguments->setCategoryId($command->getDestinationCategoryId());

            $row = $this->productToSQLGenerator->generate($arguments);
            file_put_contents($dumpsFileName, $row, FILE_APPEND);

            $images = $product->getImages() ?? [];
            foreach ($images as $image) {
                $array = pathinfo($image);
                $content = $this->getContent("https://satmaster.kiev.ua$image");
                $fileName = "/tmp/graber/{$command->getUserId()}/images/{$array['basename']}";
                file_put_contents($fileName, $content);
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
            /** @var string $e */
            $this->logger->error($e);
            $file->whiteState(new Error('Ошибка сервера. Обратитесь к администратору.'));
        }
    }
}