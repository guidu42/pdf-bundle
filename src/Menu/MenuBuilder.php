<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder
{
    private $factory;

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'main-list d-flex justify-content-center flex-column',
            ]
        ]);

        $menu->addChild('Home', [
            'route' => 'admin',
            'label' => 'menu.home.title.label',
            'extras' => array('safe_label' => true, 'icon' =>'fas fa-home', 'selected_route' => 'admin'),
        ]);

        $menu->addChild('Custom Page Index', [
            'route' => 'custom_page_index',
            'label' => 'menu.custom-page.title.label',
            'extras' => array('safe_label' => true, 'icon' =>'fas fa-pen', 'selected_route' => 'custom_page_index'),
        ]);

        //ADD A CHILD HERE
//        $menu['Custom Page Index']
//            ->addChild('New', [
//                'route' => 'custom_page_new',
//                'label' => 'menu.custom-page-new.title.label',
//                ]);

        // foreach ($menu as $child) {
        //   echo $child->getLabel();
        // }

        return $menu;
    }
}
