<?php

// контроллер для обновления пунктов Amazon-меню шаблона Revolution
class ControllerEventUpdateAmazonMenu extends Controller
{
    // обновляет пункты Amazon-меню в БД
    public function index(&$route, &$data, &$output)
    {
        $this->load->model('setting/setting');

        $jsonAmazonMenu = $this->getJsonForAmazonMenu();
        $this->model_setting_setting->editSettingValue('revtheme', 'revtheme_dop_menu', $jsonAmazonMenu);
    }

    // возвращает дерево категорий
    public function getCategoryTree()
    {
        $categoryTree = array();

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG);

        // загружаю функцию, которая будет превращать обычные ссылки в SEO URL
        if ($this->config->get('config_seo_url')) {
            require_once modification(DIR_CATALOG . 'controller/startup/seo_url.php');
            $rewriter = new ControllerStartupSeoUrl($this->registry);
            $url->addRewrite($rewriter);
        }

        $setting = $this->config->get('revtheme_header_menu');

        if (VERSION >= 2.2) {
            $config_image_category_width = $this->config->get($this->config->get('config_theme') . '_image_category_width');
            $config_image_category_height = $this->config->get($this->config->get('config_theme') . '_image_category_height');
        } else {
            $config_image_category_width = $this->config->get('config_image_category_width');
            $config_image_category_height = $this->config->get('config_image_category_height');
        }

        $categories = $this->model_catalog_category->getSubcategories(0);
        foreach ($categories as $category) {
            if ($category['top']) {
                $children_data = array();

                $children = $this->model_catalog_category->getSubcategories($category['category_id']);

                foreach ($children as $child) {
                    $children_data_level2 = array();
                    if (!$setting['image_in_ico'] || $setting['tri_level']) {
                        $children_level2 = $this->model_catalog_category->getSubcategories($child['category_id']);
                        foreach ($children_level2 as $child_level2) {
                            $data_level2 = array(
                                'filter_category_id'  => $child_level2['category_id'],
                                'filter_sub_category' => true
                            );

                            $filter_data_2 = array(
                                'filter_category_id'  => $child_level2['category_id'],
                                'filter_sub_category' => true
                            );

                            $children_data_level2[] = array(
                                'name'  =>  $child_level2['name'] . ($this->config->get('config_product_count') ? ' <sup>' . $this->model_catalog_product->getTotalProducts($filter_data_2) . '</sup>' : ''),
                                'category_id' => $child_level2['category_id'],
                                'href'  => $url->link('product/category', 'path=' . $child['category_id'] . '_' . $child_level2['category_id'])
                            );
                        }
                    }

                    $filter_data_1 = array(
                      'filter_category_id'  => $child['category_id'],
                      'filter_sub_category' => true
                    );

                    $child_info = $this->model_catalog_category->getCategory($child['category_id']);
                    if ($child_info) {
                        if ($child_info['image']) {
                            $thumb = $child_info['image'];
                        } else {
                            $thumb = '';
                        }
                        if ($setting['image_in_ico']) {
                            $style = ' hidden';
                        } else {
                            $style = '';
                        }
                        if ($child_info['category_icontype']) {
                            if ($child_info['category_icon'] == 'fa none') {
                                $style = ' hidden';
                            }
                            $category_image = '<i class="am_category_icon '.$child_info['category_icon'].$style.'"></i>';
                        } else {
                            if (!$child_info['category_image'] || $child_info['category_image'] == 'no_image.png') {
                                $style = ' hidden';
                            }
                            $category_image = '<span class="am_category_image'.$style.'"><img src="'.$this->model_tool_image->resize($child_info['category_image'], 21, 21).'" alt=""/></span>';
                        }
                    }

                    $children_data[] = array(
                      'name'        	 => $child['name'] . ($this->config->get('config_product_count') ? ' <sup>' . $this->model_catalog_product->getTotalProducts($filter_data_1) . '</sup>' : ''),
                      'thumb' 		 => $thumb,
                      'category_image' => $category_image,
                      'category_id' 	 => $child['category_id'],
                      'children'   	 => $children_data_level2,
                      'href'        	 => $url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }
                $category_info = $this->model_catalog_category->getCategory($category['category_id']);
                if ($category_info) {
                    if ($category_info['image2']) {
                        $thumb2 = $category_info['image2'];
                    } else {
                        $thumb2 = '';
                    }

                    $style = '';
                    if ($category_info['category_icontype']) {
                        if ($category_info['category_icon'] == 'fa none') {
                            $style = ' hidden';
                        }
                        $category_image = '<i class="am_category_icon '.$category_info['category_icon'].$style.'"></i>';
                    } else {
                        if (!$category_info['category_image'] || $category_info['category_image'] == 'no_image.png') {
                            $style = ' hidden';
                        }
                        $category_image = '<span class="am_category_image'.$style.'"><img src="'.$this->model_tool_image->resize($category_info['category_image'], 21, 21).'" alt=""/><span class="mask"></span></span>';
                    }
                }

                $filter_data = array(
                    'filter_category_id'  => $category['category_id'],
                    'filter_sub_category' => true
                );

                $categoryTree[] = array(
                    'category_id' 	 => $category['category_id'],
                    'name'        	 => $category['name'] . ($this->config->get('config_product_count') ? ' <sup>' . $this->model_catalog_product->getTotalProducts($filter_data) . '</sup>' : ''),
                    'thumb2'      	 => $thumb2,
                    'category_image' => $category_image,
                    'children'    	 => $children_data,
                    'href'        	 => $url->link('product/category', 'path=' . $category['category_id']),
                    'column'      	 => $category['column'] ? $category['column'] : 1,
                );
            }
        }

        return $categoryTree;
    }

    // генерирует массив, подходящий для вывода Amazon-меню
    public function getArrayForAmazonMenu($categoryTree, $currentId = 0)
    {
        $arrayAmazonMenu = array();
        static $currentId = 0;

        foreach ($categoryTree as $category) {
            $currentId += 1;

            $categoryData = [
              'id' => $currentId,
              'name1' => $category['name'],
              'name2' => $category['name'],
              'href1' => $category['href'],
              'href2' => $category['href'],
              'icontype' => 'image',
              'dop_menu_iconka' => '',
              'column' => '',
          ];

            if (isset($category['thumb'])) {
                $categoryData['dop_menu_image'] = $category['thumb'];
            }
            if (isset($category['thumb2'])) {
                $categoryData['dop_menu_image'] = $category['thumb2'];
            }

            if (isset($category['children']) && ($category['children'] != null)) {
                $categoryData[''] = [
                  'collapsed' => false
                  ];
                $categoryData['children'] = $this->getArrayForAmazonMenu($category['children'], $currentId);
            }

            $arrayAmazonMenu[] = $categoryData;
        }

        return $arrayAmazonMenu;
    }

    // генерирует JSON, подходящий для вывода Amazon-меню
    public function getJsonForAmazonMenu()
    {
        $jsonAmazonMenu = '';

        $categoryTree = $this->getCategoryTree();
        $arrayAmazonMenu = $this->getArrayForAmazonMenu($categoryTree);
        $jsonAmazonMenu = json_encode($arrayAmazonMenu, JSON_UNESCAPED_UNICODE);

        return $jsonAmazonMenu;
    }
}
