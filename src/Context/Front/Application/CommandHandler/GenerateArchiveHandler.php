<?php

namespace App\Context\Front\Application\CommandHandler;

use Throwable;
use ZipArchive;
use JsonSerializable;
use Symfony\Component\Filesystem\Filesystem;
use App\Context\Parser\Domain\ValueObject\URL;
use App\Context\Front\Application\Command\GenerateArchive;
use App\Context\Front\Application\CommandHandler\Step\Error;
use App\Context\Front\Application\CommandHandler\Step\Finish;
use App\Context\Front\Application\CommandHandler\Step\Process;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use App\Context\Front\Application\CommandHandler\Step\Initialization;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Context\Front\Application\Command\GenerateArchiveHandler as Base;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Arguments;
use App\Context\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Context\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class GenerateArchiveHandler implements Base
{
    private Filesystem $filesystem;

    private ProductParser $productParser;

    private CategoryParser $categoryParser;

    private ProductToSQLGenerator $productToSQLGenerator;

    /**
     * @param Filesystem $filesystem
     * @param ProductParser $productParser
     * @param CategoryParser $categoryParser
     * @param ProductToSQLGenerator $productToSQLGenerator
     */
    public function __construct(
        Filesystem $filesystem,
        ProductParser $productParser,
        CategoryParser $categoryParser,
        ProductToSQLGenerator $productToSQLGenerator
    )
    {
        $this->filesystem = $filesystem;
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
        mkdir("/tmp/graber/{$command->getUserId()}/dumps", 0777, true);
        mkdir("/tmp/graber/{$command->getUserId()}/images", 0777, true);
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    private function removeFolders(GenerateArchive $command): void
    {
        $this->filesystem->remove("/tmp/graber/{$command->getUserId()}");
    }

    /**
     * @param GenerateArchive $command
     * @param JsonSerializable $state
     * @return void
     */
    private function setState(GenerateArchive $command, JsonSerializable $state): void
    {
        file_put_contents("/tmp/graber/{$command->getUserId()}.json", json_encode($state));
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    private function createArchive(GenerateArchive $command): void
    {
        $fileName = "/tmp/graber/{$command->getUserId()}.zip";
        if (true === file_exists($fileName)) {
            $this->filesystem->remove($fileName);
        }

        $zip = new ZipArchive();
        $zip->open($fileName, ZipArchive::CREATE);

        foreach (scandir("/tmp/graber/{$command->getUserId()}/dumps") as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("dumps/$path", $path);
            }
        }

        foreach (scandir("/tmp/graber/{$command->getUserId()}/images") as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("images/$path", $path);
            }
        }

        $zip->close();
    }

    /**
     * @param GenerateArchive $command
     * @return void
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function invoke(GenerateArchive $command): void
    {
        $calculatePercent = static fn(int $current, int $total): int => (int)(($current * 100) / $total);

        $this->createFolders($command);
        $this->setState($command, new Initialization('Получаем список товаров'));
        $productsUrls = $this->categoryParser->parse(new URL($command->getSourceCategoryUrl()));
        $this->setState($command, new Initialization('Список товаров успешно получен'));

        foreach ($productsUrls as $index => $productUrl) {
            $product = $this->productParser->parse(new URL($productUrl));

            $arguments = new Arguments($product);
            $arguments->setImagePath($command->getDestinationImagesPath());
            $arguments->setCategoryId($command->getDestinationCategoryId());
            $this->productToSQLGenerator->generate($arguments);

            $this->setState($command, new Process($calculatePercent($index + 1, count($productsUrls)), 'Обрабатываем товары'));
        }

        $this->createArchive($command);
        $this->removeFolders($command);

        $this->setState($command, new Finish("/archive/{$command->getUserId()}.zip", 'Архив успешно создан'));
    }

    /**
     * @param GenerateArchive $command
     * @return void
     */
    public function __invoke(GenerateArchive $command): void
    {
        try {
            $this->invoke($command);
        }  catch (Throwable $e) {
            $this->setState($command, new Error('Ошибка сервера. Обратитесь к администратору.'));
        }
    }
}