<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfonycasts\SassBundle\Command\SassBuildCommand;
use Symfonycasts\SassBundle\SassBuilder;
use Symfonycasts\SassBundle\AssetMapper\SassCssCompiler;
use Symfonycasts\SassBundle\AssetMapper\SassPublicPathAssetPathResolver;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('sass.builder', SassBuilder::class)
            ->args([
                abstract_arg('path to sass files'),
                abstract_arg('path to css directory'),
                param('kernel.project_dir'),
                abstract_arg('path to binary'),
                abstract_arg('embed sourcemap'),
            ])

        ->set('sass.command.build', SassBuildCommand::class)
            ->args([
                service('sass.builder')
            ])
            ->tag('console.command')

        ->set('sass.css_asset_compiler', SassCssCompiler::class)
            ->tag('asset_mapper.compiler', [
                'priority' => 10
            ])
            ->args([
                abstract_arg('path to scss files'),
                abstract_arg('path to css output directory'),
                service('sass.builder'),
            ])
        ->set('sass.public_asset_path_resolver', SassPublicPathAssetPathResolver::class)
        ->decorate('asset_mapper.public_assets_path_resolver')
        ->args([
            service('.inner')
        ])
    ;
};
