<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Core\BaksDevCoreBundle;
use BaksDev\Core\Listeners\Entity\EntityKeyValueCache;
use BaksDev\Core\Repository\SettingsMain\SettingsMainInterface;
use BaksDev\Core\Repository\SettingsMain\SettingsMainRepository;
use BaksDev\Core\Routing\BaksRoutingLoader;
use BaksDev\Core\Type\Crypt\CryptKey;
use BaksDev\Core\Type\Crypt\CryptKeyInterface;

return static function (ContainerConfigurator $container): void {

    //    $services = $container->services()
    //        ->defaults()
    //        ->autowire()
    //        ->autoconfigure();
    //
    //    $NAMESPACE = $NAMESPACE;
    //    $PATH = $PATH;
    //
    //    /* Language */
    //    $services->load($NAMESPACE.'Type\Locale\Locales\\', $PATH.'Type/Locale/Locales');
    //
    //    /* Device */
    //    $services->load($NAMESPACE.'Type\Device\Devices\\', $PATH.'Type/Device/Devices');
    //
    //    /* Модификаторы */
    //    $services->load($NAMESPACE.'Type\Modify\Modify\\', $PATH.'Type/Modify/Modify');
    //
    //
    //    /** @see https://symfony.com/doc/current/service_container/autowiring.html#dealing-with-multiple-implementations-of-the-same-type */
    //    $services->alias(SettingsMainInterface::class, SettingsMainRepository::class);
    //
    //    $services->alias(CryptKeyInterface::class, CryptKey::class);


    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    /** Значение по умолчанию таблиц REDIS */
    $container->parameters()->set('DEFAULT_REDIS_TABLE', '0');

    $NAMESPACE = BaksDevCoreBundle::NAMESPACE;
    $PATH = BaksDevCoreBundle::PATH;

    $services->load($NAMESPACE, $PATH)
        ->exclude([
            $PATH.'{Entity,Resources,Type}',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*Message.php',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*Result.php',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*DTO.php',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*Test.php',
            // $PATH.'**'.DIRECTORY_SEPARATOR.'regions.php',
        ]);


    /* Language */
    $services->load(
        $NAMESPACE.'Type\Locale\Locales\\',
        $PATH.implode(DIRECTORY_SEPARATOR, ['Type', 'Locale', 'Locales']) // 'Type/Locale/Locales'
    );

    /* Device */
    $services->load(
        $NAMESPACE.'Type\Device\Devices\\',
        $PATH.implode(DIRECTORY_SEPARATOR, ['Type', 'Device', 'Devices']) //'Type/Device/Devices'
    );

    /* Модификаторы */
    $services->load(
        $NAMESPACE.'Type\Modify\Modify\\',
        $PATH.implode(DIRECTORY_SEPARATOR, ['Type', 'Modify', 'Modify']) //'Type/Modify/Modify'
    );


    /** @see https://symfony.com/doc/current/service_container/autowiring.html#dealing-with-multiple-implementations-of-the-same-type */
    $services->alias(
        SettingsMainInterface::class,
        SettingsMainRepository::class
    );

    $services->alias(
        CryptKeyInterface::class,
        CryptKey::class
    );

    $services
        ->set(BaksRoutingLoader::class)
        ->tag('routing.loader');


    $services
        ->set(EntityKeyValueCache::class)
        ->tag('doctrine.event_listener', ['event' => 'postFlush']);


};
