<?php
declare(strict_types=1);
namespace Helhum\Typo3Console\Mvc\Cli;

/*
 * This file is part of the TYPO3 Console project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 *
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Reflection\ClassSchema\Method;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

/**
 * Analyze command method arguments and tags with PHP reflection
 */
class CommandReflection
{
    /**
     * @var Method
     */
    private $methodProperties;

    public function __construct(string $controllerClassName, string $controllerCommandMethod, ReflectionService $reflectionService = null)
    {
        $reflectionService = $reflectionService ?: GeneralUtility::makeInstance(ObjectManager::class)->get(ReflectionService::class);
        $this->methodProperties = $reflectionService->getClassSchema($controllerClassName)->getMethod($controllerCommandMethod);
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getParameters(): array
    {
        $params_ = $this->methodProperties->getParameters();
        $params = [];

        foreach ($params_ as $key => $param) {
            $params[$key] =  [
                'type' => $param->getType(),
                'defaultValue' => $param->getDefaultValue(),

                'private'     => false,
                'protected'   => true,
                'public'      => true,
                'static'      => false,
                //'type'        => null, // Extbase
                //'elementType' => null, // Extbase
                'annotations' => [],
                'tags'        => [],
                'validators'  => []
            ];
        }
        return $params;
    }

    public function getTagsValues(): array
    {
        return [];
    }
}
