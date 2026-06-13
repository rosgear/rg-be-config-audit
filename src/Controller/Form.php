<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\Config\Audit\Controller;

use Ge;
use Ge\Panel\Helper\Ext;
use Ge\Panel\Helper\ExtCombo;
use Ge\Panel\Widget\EditWindow;
use Rg\Backend\Config\Controller\ServiceForm;

/**
 * Контроллер конфигурации службы "Аудит".
 * 
 * Cлужба {@see \Ge\Panel\Audit\Audit}.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\Config\Audit\Controller
 * @since 1.0
 */
class Form extends ServiceForm
{
    /**
     * Возвращает элементы панели формы (Ge.view.form.Panel GeJS).
     * 
     * @return array
     */
    protected function getFormItems(): array
    {
        // максимальное количество записей
        $limit = Ge::$app->audit->storage['limit'] ?? 1000;
        // хранилизе записей
        $storage = Ge::$app->audit->getStorage();
        $storageName = $storage ? $storage->getShortClass() : '';

        return [
            ExtCombo::local('#Record storage', 'storageName', [
                'fields' => ['id', 'name'],
                'data'   => [['id' => 'DbStorage', 'name' => '#Database']]
                ], 
                ['value' => $storageName, 'allowBlank' => false]
            ),
            [
                'xtype'      => 'numberfield',
                'name'       => 'limit',
                'fieldLabel' => '#Maximum number of entries',
                'tooltip'    => '#The maximum number of audit records in the vault. If it exceeds the maximum, the entries are deleted.',
                'minValue'   => 10,
                'maxValue'   => 10000,
                'value'      => $limit,
                'width'      => 350,
                'allowBlank' => false
            ],
            Ext::switch(
                '#Audit service enabled',
                'enabled',
                Ge::$app->audit->enabled
            ),
            [
                'xtype'    => 'fieldset',
                'title'    => '#The audit includes the following information',
                'defaults' => [
                    'labelWidth' => 250,
                    'labelAlign' => 'right'
                ],
                'items' => [
                    Ext::switch(
                        '#user information',
                        'userSection',
                        Ge::$app->audit->hasSection('user')
                    ),
                    Ext::switch(
                        '#request controller information',
                        'controllerSection',
                        Ge::$app->audit->hasSection('controller')
                    ),
                    Ext::switch(
                        '#request module information',
                        'moduleSection',
                        Ge::$app->audit->hasSection('module')
                    ),
                    Ext::switch(
                        '#request parameter information',
                        'requestSection',
                        Ge::$app->audit->hasSection('request')
                    ),
                    Ext::switch(
                        '#user device information',
                        'deviceSection',
                        Ge::$app->audit->hasSection('device')
                    ),
                ]
            ],
            [
                'xtype'  => 'toolbar',
                'dock'   => 'bottom',
                'border' => 0,
                'style'  => ['borderStyle' => 'none'],
                'items'  => [
                    [
                        'xtype'    => 'checkbox',
                        'boxLabel' => $this->module->t('reset settings'),
                        'name'     => 'reset',
                        'ui'       => 'switch'
                    ]
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        // проверка работы службы
        if (!Ge::$services->hasInvokableClass('audit')) {
            $this->getResponse()
                ->meta->error(Ge::t('app', 'Unable to call service "{0}"', ['audit']));
            return false;
        }

        /** @var \Ge\Panel\Widget\EditWindow $window */
        $window = parent::createWidget();

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->autoHeight = true;
        $window->width = 470;

        // панель формы (Ge.view.form.Panel GeJS)
        $window->form->items = $this->getFormItems();
        $window->form->bodyPadding = 7;
        $window->form->defaults = [
            'labelWidth' => 250,
            'labelAlign' => 'right'
        ];
        return $window;
    }
}
