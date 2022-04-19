<?php

namespace App\Domain\Common\Application\Provider\LanguageProvider;

use App\Domain\Common\Domain\Enum\ConfigEnum;
use App\Domain\Common\Application\Config\ConfigManager;
use App\Domain\Common\Domain\Entity\Base\Front\Language as LanguageFront;
use App\Domain\Common\Application\Provider\LanguageProvider\Repository\Front\LanguageRepository as LanguageFrontRepository;

class Provider
{
    private ConfigManager $configManager;

    private LanguageFrontRepository $languageFrontRepository;

    /**
     * @param ConfigManager $configManager
     * @param LanguageFrontRepository $languageFrontRepository
     */
    public function __construct(
        ConfigManager $configManager,
        LanguageFrontRepository $languageFrontRepository
    )
    {
        $this->configManager = $configManager;
        $this->languageFrontRepository = $languageFrontRepository;
    }

    /**
     * @return LanguageFront
     */
    public function getDefaultLanguageFront(): LanguageFront
    {
        $id = $this->configManager->getInt(ConfigEnum::FRONT_DEFAULT_LANGUAGE_ID);

        /** @psalm-var LanguageFront */
        return $this->languageFrontRepository->findOne($id);
    }
}