<?php
/**
 * Расширение модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Audit;

/**
 * Расширение "Аудит".
 * 
 * Настройка аудита.
 * 
 * Расширение принадлежит модулю "Конфигурация".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Audit
 * @since 1.0
 */
class Extension extends \Ge\Panel\Extension\Extension
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'rg.be.config.audit';

    /**
     * {@inheritdoc}
     */
    public string $defaultController = 'form';
}