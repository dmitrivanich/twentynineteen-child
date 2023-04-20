<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

function custom_post_type_movies() {
    $labels = array(
        'name'               => 'Фильмы',
        'singular_name'      => 'Фильм',
        'menu_name'          => 'Фильмы',
        'name_admin_bar'     => 'Фильм',
        'add_new'            => 'Добавить новый',
        'add_new_item'       => 'Добавить новый фильм',
        'new_item'           => 'Новый фильм',
        'edit_item'          => 'Редактировать фильм',
        'view_item'          => 'Просмотреть фильм',
        'all_items'          => 'Все фильмы',
        'search_items'       => 'Искать фильмы',
        'not_found'          => 'Фильмы не найдены',
        'not_found_in_trash' => 'Фильмы не найдены в корзине',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
		'show_in_rest'        => true,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'          => array( 'genres', 'countries', 'actors' ),
    );

    register_post_type( 'movie', $args );
}

add_action( 'init', 'custom_post_type_movies', 0 );

//Регистрация таксономий
function custom_taxonomies_movies() {
    $genre_labels = array(
        'name'                       => 'Жанры',
        'singular_name'              => 'Жанр',
        'menu_name'                  => 'Жанры',
        'all_items'                  => 'Все жанры',
        'edit_item'                  => 'Редактировать жанр',
        'view_item'                  => 'Просмотреть жанр',
        'update_item'                => 'Обновить жанр',
        'add_new_item'               => 'Добавить новый жанр',
        'new_item_name'              => 'Новое имя жанра',
        'parent_item'                => 'Родительский жанр',
        'parent_item_colon'          => 'Родительский жанр:',
        'search_items'               => 'Искать жанры',
        'popular_items'              => 'Популярные жанры',
        'separate_items_with_commas' => 'Разделять жанры запятыми',
        'add_or_remove_items'        => 'Добавить или удалить жанры',
        'choose_from_most_used'      => 'Выбрать из наиболее используемых жанров',
        'not_found'                  => 'Жанры не найдены',
        'no_terms'                   => 'Нет жанров',
        'items_list_navigation'      => 'Навигация по списку жанров',
        'items_list'                 => 'Список жанров',
        'most_used'                  => 'Наиболее используемые',
        'back_to_items'              => 'Вернуться к жанрам',
    );

    $genre_args = array(
        'labels'                     => $genre_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_admin_column'          => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'genres',
        'show_in_quick_edit'         => true,
    );
	
	// Регистрация таксономии "Жанры"
    register_taxonomy( 'genres', 'movie', $genre_args );

	
	 $actors_labels = array(
		'name'                       => 'Актёры',
		'singular_name'              => 'Актёр',
		'menu_name'                  => 'Актёры',
		'all_items'                  => 'Все актёры',
		'edit_item'                  => 'Редактировать актёра',
		'view_item'                  => 'Просмотреть актёра',
		'update_item'                => 'Обновить актёра',
		'add_new_item'               => 'Добавить нового актёра',
		'new_item_name'              => 'Новое имя актёра',
		'parent_item'                => 'Родительский актёр',
		'parent_item_colon'          => 'Родительский актёр:',
		'search_items'               => 'Искать актёров',
		'popular_items'              => 'Популярные актёры',
		'separate_items_with_commas' => 'Разделять актёров запятыми',
		'add_or_remove_items'        => 'Добавить или удалить актёров',
		'choose_from_most_used'      => 'Выбрать из наиболее используемых актёров',
		'not_found'                  => 'Актёры не найдены',
		'no_terms'                   => 'Нет актёров',
		'items_list_navigation'      => 'Навигация по списку актёров',
		'items_list'                 => 'Список актёров',
		'most_used'                  => 'Наиболее используемые',
		'back_to_items'              => 'Вернуться к актёрам',
	);


		$actors_args = array(
			'labels'                     => $actors_labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_admin_column'          => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'actors',
			'show_in_quick_edit'         => true,
		);
		// Регистрация таксономии "Актёры"
		register_taxonomy( 'actors', 'movie', $actors_args );

		
		$countries_labels = array(
			'name'                       => 'Cтраны',
			'singular_name'              => 'Cтрана',
			'menu_name'                  => 'Cтраны',
			'all_items'                  => 'Все страны',
			'edit_item'                  => 'Редактировать страну',
			'view_item'                  => 'Просмотреть страну',
			'update_item'                => 'Обновить страну',
			'add_new_item'               => 'Добавить новую страну',
			'new_item_name'              => 'Новое имя страны',
			'parent_item'                => 'Родительская страна',
			'parent_item_colon'          => 'Родительская страна:',
			'search_items'               => 'Искать страны',
			'popular_items'              => 'Популярные страны',
			'separate_items_with_commas' => 'Разделять страны запятыми',
			'add_or_remove_items'        => 'Добавить или удалить страны',
			'choose_from_most_used'      => 'Выбрать из наиболее используемых стран',
			'not_found'                  => 'Страны не найдены',
			'no_terms'                   => 'Нет стран',
			'items_list_navigation'      => 'Навигация по списку стран',
			'items_list'                 => 'Список стран',
			'most_used'                  => 'Наиболее используемые',
			'back_to_items'              => 'Вернуться к странам',
		);

		$countries_args = array(
			'labels'                     => $countries_labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_admin_column'          => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'countries',
			'show_in_quick_edit'         => true,
		);
	
		// Регистрация таксономии "Cтраны"
		register_taxonomy( 'countries', 'movie', $countries_args );

}

add_action( 'init', 'custom_taxonomies_movies', 0 );

