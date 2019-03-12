<?php
namespace WordPressPopularPosts\Container;

use WordPressPopularPosts\Settings;

class WordPressPopularPostsConfiguration implements ContainerConfigurationInterface
{
    /**
     * Modifies the given dependency injection container.
     *
     * @since   5.0.0
     * @param   Container $container
     */
    public function modify(Container $container)
    {
        $container['admin_options'] = Settings::get('admin_options');
        $container['widget_options'] = Settings::get('widget_options');

        $container['translate'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Translate();
        });

        $container['image'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Image();
        });

        $container['output'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Output($container['widget_options'], $container['admin_options'], $container['image'], $container['translate']);
        });

        $container['widget'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Widget\Widget($container['widget_options'], $container['admin_options'], $container['output'], $container['image']);
        });

        $container['rest'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Rest\Controller($container['admin_options'], $container['translate']);
        });

        $container['front'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\Front\Front($container['admin_options'], $container['translate'], $container['output']);
        });

        $container['wpp'] = $container->service(function(Container $container) {
            return new \WordPressPopularPosts\WordPressPopularPosts($container['rest'], $container['front'], $container['widget']);
        });
    }
}