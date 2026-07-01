<?php
if (is_front_page()) {
	get_template_part('templates/front-page');
} elseif (is_404()) {
	get_template_part('templates/404');
} elseif (is_search()) {
	get_template_part('templates/search');
} elseif (is_category()) {
	get_template_part('templates/category');
} elseif (is_archive()) {
	get_template_part('templates/archive');
} elseif (is_single()) {
	get_template_part('templates/single');
} elseif (is_page()) {
	get_template_part('templates/page');
} else {
	get_template_part('templates/index');
}
