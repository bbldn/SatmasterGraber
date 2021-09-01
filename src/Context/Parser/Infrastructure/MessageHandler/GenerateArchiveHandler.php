<?php

namespace App\Context\Parser\Infrastructure\MessageHandler;

use Throwable;
use ZipArchive;
use Symfony\Component\Filesystem\Filesystem;
use App\Context\Parser\Domain\ValueObject\URL;
use App\Context\Parser\Domain\Message\GenerateArchive;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Arguments;
use App\Context\Parser\Application\Common\ProductParser\Parser as ProductParser;
use App\Context\Parser\Application\Common\CategoryParser\Parser as CategoryParser;
use App\Context\Parser\Application\Common\ProductToSQLGenerator\Generator as ProductToSQLGenerator;

class GenerateArchiveHandler implements MessageHandlerInterface
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
     * @param GenerateArchive $message
     * @return void
     */
    private function createFolders(GenerateArchive $message): void
    {
        mkdir("/tmp/graber/{$message->getUserId()}/dumps", 0777, true);
        mkdir("/tmp/graber/{$message->getUserId()}/images", 0777, true);
    }

    /**
     * @param GenerateArchive $message
     * @return void
     */
    private function removeFolders(GenerateArchive $message): void
    {
        $this->filesystem->remove("/tmp/graber/{$message->getUserId()}");
    }

    /**
     * @param GenerateArchive $message
     * @param array $state
     * @return void
     */
    private function setState(GenerateArchive $message, array $state): void
    {
        file_put_contents("/tmp/graber/{$message->getUserId()}.json", json_encode($state));
    }

    /**
     * @param GenerateArchive $message
     * @return void
     */
    private function createArchive(GenerateArchive $message): void
    {
        $fileName = "/tmp/graber/{$message->getUserId()}.zip";
        if (true === file_exists($fileName)) {
            $this->filesystem->remove($fileName);
        }

        $zip = new ZipArchive();
        $zip->open($fileName, ZipArchive::CREATE);

        foreach (scandir("/tmp/graber/{$message->getUserId()}/dumps") as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("dumps/$path", $path);
            }
        }

        foreach (scandir("/tmp/graber/{$message->getUserId()}/images") as $path) {
            if ('.' !== $path && '..' !== $path) {
                $zip->addFile("images/$path", $path);
            }
        }

        $zip->close();
    }

    /**
     * @param GenerateArchive $message
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     * @return void
     */
    private function invoke(GenerateArchive $message): void
    {
        $calculatePercent = static fn(int $current, int $total): int => (int)(($current * 100) / $total);

        $this->createFolders($message);
        $this->setState($message, ['message' => 'Получаем список товаров', 'percent' => 0]);
        $productsUrls = $this->categoryParser->parse(new URL($message->getSourceCategoryUrl()));
        $this->setState($message, ['message' => 'Список товаров успешно получен', 'percent' => 0]);

        foreach ($productsUrls as $index => $productUrl) {
            $product = $this->productParser->parse(new URL($productUrl));

            $arguments = new Arguments($product);
            $arguments->setImagePath($message->getDestinationImagesPath());
            $arguments->setCategoryId($message->getDestinationCategoryId());
            $this->productToSQLGenerator->generate($arguments);

            $this->setState(
                $message,
                ['message' => 'Обрабатываем товары', 'percent' => $calculatePercent($index + 1, count($productsUrls))]
            );
        }

        $this->createArchive($message);
        $this->removeFolders($message);

        $this->setState(
            $message,
            ['message' => 'Архив успешно создан', 'url' => "/archive/{$message->getUserId()}.zip"]
        );
    }

    /**
     * @param GenerateArchive $message
     * @return void
     */
    public function __invoke(GenerateArchive $message): void
    {
        try {
            $this->invoke($message);
        }  catch (Throwable $e) {
            $this->setState($message, ['error' => 'Ошибка сервера. Обратитесь к администратору.']);
        }
    }
}