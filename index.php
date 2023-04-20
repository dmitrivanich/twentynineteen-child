<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<!-- Форма фильтрации -->
		<form class="filter-area" action="" method="get">
					
			<?php
				$args = array(
					'post_type' => 'movie',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'price',
							'compare' => 'EXISTS', // Выбрать только посты с метаполем price
						),
						array(
							'key' => 'release_date',
							'compare' => 'EXISTS', // Выбрать только посты с метаполем release_date
							'type' => 'DATE' // Указываем, что значение метаполя является датой
						)
					)
				);

				$posts = get_posts($args);

				if (!empty($posts)) {
					$max_price = 0;
					$max_release_date = '';

					foreach ($posts as $post) {
						$price = get_post_meta($post->ID, 'price', true);
						$release_date = get_post_meta($post->ID, 'release_date', true);

						if ($price > $max_price) {
							$max_price = $price;
						}

						if ($release_date > $max_release_date) {
							$max_release_date = $release_date;
						}
					}
					$max_release_date_formatted = date('Y-m-d', strtotime($max_release_date));

				} else {
					echo 'Нет постов с метаполями price и release_date';
				}
			?>
			<div id="meta_fields">
				<div id="meta_field">
					<div>
						<label for="cost_from">Стоимость от:</label>
						<input type="number" name="filter[cost_from][]" id="cost_from" step="any" value="0" />
					</div>
					<div>
						<label for="cost_to">Стоимость до:</label>
						<input type="number" name="filter[cost_to][]" id="cost_to" step="any" value="<?php echo $max_price ?>" />
					</div>
					<label for="sort_price">Сортировка:</label>
					<select name="sort_price" id="sort_price">
						<option value="">По умолчанию</option>
						<option value="ASC">По возрастанию</option>
						<option value="DESC">По убыванию</option>
					</select>
				</div>

				<div id="meta_field">
					<div>
						<label for="date_from">Дата от:</label>
						<input type="date" name="filter[date_from][]" id="date_from" value="1895-11-01" />
					</div>
					<div>
						<label for="date_to">Дата до:</label>
						<input type="date" name="filter[date_to][]" id="date_to" value="<?php echo esc_attr($max_release_date_formatted); ?>" />
					</div>
					<label for="sort_release_date">Сортировка:</label>
					<select name="sort_release_date" id="sort_release_date">
						<option value="">По умолчанию</option>
						<option value="ASC">По возрастанию</option>
						<option value="DESC">По убыванию</option>
					</select>
				</div>
			</div>



			<div id="taxonomy">
				<div id="genres">
					<?php
					// Получаем список всех жанров из таксономии 'genres'
					$genres = get_terms( 'genres', array( 'hide_empty' => false ) );

					if ( ! empty( $genres ) ) {
						foreach ( $genres as $genre ) {
							?>
							<label>
								<input type="checkbox" name="filter[genres][]"
									   value="<?php echo $genre->term_taxonomy_id;?>">
								
								<?php echo esc_html( $genre->name ); ?>
							</label><br />
							<?php
						}
					}
					?>
				</div>
				<br>
				<div id='countries'>
					<?php
					// Получаем список всех стран из таксономии 'countries'
					$countries = get_terms( 'countries', array( 'hide_empty' => false ) );

					if ( ! empty( $countries ) ) {
						foreach ( $countries as $country ) {
							?>
							<label>
								<input type="checkbox" name="filter[countries][]" value="<?php echo esc_attr( $country->term_id ); ?>">
								
								<?php echo esc_html( $country->name ); ?>
							</label><br />
							<?php
						}
					}
					?>
				</div>
				<br>
				<div id='actors'>
					<?php
					// Получаем список всех стран из таксономии 'countries'
					$actors= get_terms( 'actors', array( 'hide_empty' => false ) );

					if ( ! empty( $actors ) ) {
						foreach ( $actors as $actor ) {
							?>
							<label>
								<input type="checkbox" name="filter[actors][]" value="<?php echo esc_attr( $actor->term_id ); ?>">
								
								<?php echo esc_html( $actor->name ); ?>
							</label><br />
							<?php
						}
					}
					?>
				</div>


			</div>
			
			<input type="submit" value="Фильтровать" />
		</form>

		<?php
			if(isset($_REQUEST['filter'])){//Применить фильтры
				global $wp_query;

				// Получаем значения параметров 'sort_price' и 'sort_release_date' для сортировки
				$sort_price = isset($_GET['sort_price']) ? $_GET['sort_price'] : '';
				$sort_release_date = isset($_GET['sort_release_date']) ? $_GET['sort_release_date'] : '';

				if (!empty($sort_price)) {
				  $order = $sort_price; // Используем значение параметра 'sort_price';
				  $meta_key = "price";
				  $orderby = 'meta_value_num'; 
				} elseif (!empty($sort_release_date)) {
				  $order = $sort_release_date; // Используем значение параметра 'sort_release_date'
				  $meta_key = "release_date";
				  $orderby = 'meta_value'; 
				} else {
				  $order = 'ASC'; // Используем значение 'ASC' по умолчанию;
				  $meta_key = "release_date";
				  $orderby = 'meta_value'; 
				};

				$query = Array(
					'post_type' => 'movie',
					'posts_per_page' => -1,
					'meta_query' => array(),
					'tax_query' => Array(
						'relation' => "AND",
					),
					'orderby' => $orderby, // Указываем мета поле для сортировки
					'meta_key' => $meta_key, // Указываем мета ключ
					'order' => $order,
				);

				//Фильтрация по жанру
				if(isset($_REQUEST['filter']['genres']) && is_array($_REQUEST['filter']['genres'])){
					$genres = Array();
					foreach ($_REQUEST['filter']['genres'] as $genre){
						$genres[] = intval($genre);
					}

					$query['tax_query'][] = Array(
						'taxonomy' => 'genres',
						'field' => 'term_id',
						'terms' => $genres
					);
					unset($genres);
				}

				//Фильтрация по стране
				if(isset($_REQUEST['filter']['countries']) && is_array($_REQUEST['filter']['countries'])){
					$countries = Array();
					foreach ($_REQUEST['filter']['countries'] as $country){
						$countries[] = intval($country);
					}

					$query['tax_query'][] = Array(
						'taxonomy' => 'countries',
						'field' => 'term_id',
						'terms' => $countries
					);
					
					unset($countries);
				}

				//Фильтрация по актёрам
				if(isset($_REQUEST['filter']['actors']) && is_array($_REQUEST['filter']['actors'])){
					$actors = Array();
					foreach ($_REQUEST['filter']['actors'] as $actor){
						$actors[] = intval($actor);
					}

					$query['tax_query'][] = Array(
						'taxonomy' => 'actors',
						'field' => 'term_id',
						'terms' => $actors
					);
					
					unset($actors);
				}
				
				// Добавляем фильтрацию по стоимости, если значения заданы
				$cost_from = isset($_REQUEST['filter']['cost_from']) ? floatval($_REQUEST['filter']['cost_from'][0]) : null;
				$cost_to = isset($_REQUEST['filter']['cost_to']) ? floatval($_REQUEST['filter']['cost_to'][0]) : null;
				
				if (isset($cost_from) && isset($cost_to)) {
					$query['meta_query'][] = Array(
						'key' => 'price', // Название мета-поля стоимости
						'value' => array($cost_from, $cost_to),
						'type' => 'DECIMAL',
						'compare'=> 'BETWEEN', // Оператор сравнения
					);
				}
				
				// Добавляем фильтрацию по дате, если значения заданы
				$date_from = isset($_REQUEST['filter']['date_from']) ? sanitize_text_field($_REQUEST['filter']['date_from'][0]) : null;
				$date_to = isset($_REQUEST['filter']['date_to']) ? sanitize_text_field($_REQUEST['filter']['date_to'][0]) : null;
				
				if (isset($date_from) && isset($date_to)) {
					$query['meta_query'][] = Array(
						'key' => 'release_date', // Название мета-поля стоимости
						'value' => array($date_from, $date_to),
						'type' => 'DATE',
						'compare'=> 'BETWEEN', // Оператор сравнения
					);
				}
				
				//Применение фильтра
				$new_query = new WP_Query($query);
				$wp_query = $new_query;

				// Если найдены фильмы
				if ($wp_query->have_posts()) {
					while ($wp_query->have_posts()) {
						$wp_query->the_post();
						get_template_part( 'content/content' ); // Используйте свой шаблон для отображения содержимого поста
					}
					wp_reset_postdata(); // Сбрасываем данные постов
				} else {
					if (isset($cost_from) && isset($cost_to)) {
						echo '<p id="none">';
						print_r('От $' . $cost_from . ' ');
						print_r('до $' . $cost_to . ' ');
						echo '</p>';
					}
					if (isset($date_from) && isset($date_to)) {
						echo '<p id="none">';
						print_r('От ' . $date_from . ' ');
						print_r('до ' . $date_to . ' ');
						echo '</p>';
					}
											
					echo '<p id="none">Фильмы не найдены.</p>';
				}
			} else {//Показать все фильмы
				
				$args = array(
					'post_type' => 'movie',
					'posts_per_page' => -1,
				);

				$movie_query = new WP_Query( $args );
				
				if ( $movie_query->have_posts() ) {

					// Load posts loop.
					while ( $movie_query->have_posts() ) {
						$movie_query->the_post();
						get_template_part( 'content/content' );
					}
	
					// Previous/next page navigation.
					twentynineteen_the_posts_navigation();
	
				} else {
	
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );
	
				}
			}
		?>

		

</main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();