<?php

$content_display_mode = false;

if (user_id() == 0) {
	$cache_page = true;
} else {
	$cache_page = false;

}

if (defined('INTERNAL_API_CALL') == true) {
	$microweber_api = $this;
	$CI = get_instance();
	return $CI;

} else {

	//require (APPPATH . 'controllers/advanced/users/force_profile_complete.php');

	$output_format = false;
	$subdomain_user = get_instance()-> session -> userdata('subdomain_user');
	get_instance()-> template['subdomain_user'] = $subdomain_user;
	$subdomain_user = false;
	if ($content_display_mode != 'extended_api_with_no_template') {

	}

	$site_cache_time = "5 minutes";
	$url = url();
	//p($url);
	$url = str_ireplace('\\', '', $url);
	$cache_content = false;
	$whole_site_cache_id = 'url_' . md5($url);
	$cache_page = false;
	if ($cache_page == true) {
		if (!$_POST) {

			$cache_content = get_instance()-> core_model -> cacheGetContentAndDecode($whole_site_cache_id, $cache_group = 'global', $site_cache_time);
			//	p($cache_content);
		}
	}

	$is_json = url_param('json');
	if ($is_json) {
		$output_format = 'json';
		$url = url_param_unset('json', $url);
	}

	$is_debug = url_param('debug');
	if ($is_debug) {

		$url = url_param_unset('debug', $url);
	}

	$is_debug = url_param('?editmode');
	if ($is_debug) {

		$url = url_param_unset('editmode', $url);
	}
	//p($output_format);
	//$cache_content = false;

	if (($cache_content) != false) {

		$layout = $cache_content;
		get_instance()-> output -> set_output($layout);
		//CI::library('output')->set_output ( $layout );
	} else {
		$slash = substr("$url", 0, 1);
		if ($slash == '/') {
			$url = substr("$url", 1, strlen($url));
		}

		if (trim($url) == '') {
			//
			$page = get_instance()-> content_model -> getContentHomepage();

			//	var_dump($page);
		} else {
			 
				if ($page['is_home'] != 'y') {
					$post_maybe = get_instance()-> content_model -> getContentByURLAndCache($url);
				}
			 
			if (!empty($post_maybe)) {
				if (intval($post_maybe['id']) != 0) {
					$post_maybe = get_instance()-> content_model -> contentGetByIdAndCache($post_maybe['id']);
				}
			}
			$page = get_instance()-> content_model -> getPageByURLAndCache($url);
		}
		if (empty($page)) {

			if (is_file(TEMPLATES_DIR . 'layouts/' . $url . '/index.php') == true) {
				if (is_file(TEMPLATES_DIR . 'layouts/' . $url . '/config.php') == true) {
					$new_page_conf = TEMPLATES_DIR . 'layouts/' . $url . '/config.php';
				}
				$to_save = array();
				$to_save['content_title'] = $url;
				$to_save['content_url'] = $url;
				$to_save['content_type'] = 'page';
				$to_save['content_layout_name'] = $url;
				$to_save['content_layout_file'] = $url . '/index.php';

				$new_page = get_instance()-> content_model -> saveContent($to_save);
				//	p($new_page,1);
				//$page = $this->content_model->getPageByURLAndCache ( $url );
				safe_redirect(site_url($url));
				exit();

				//p($new_page_conf,1);

				//	p ( TEMPLATES_DIR . 'layouts/' . $url );

				//p ( $url, 1 );
			} else {

				//return false;
			}

			//exit ( '404: Nothing found on line ' . __LINE__ );
		}
		//var_dump($page);
		if (empty($page)) {
			$page = get_instance()-> content_model -> getContentHomepage();
		}

		/*if (is_readable ( TEMPLATES_DIR . 'layouts/' . $content ['content_layout_file'] ) == true) {

		 }*/
		//p($post);
		//	p($page);
		 
			if ($post_maybe['content_type'] == 'post') {
				$post = $post_maybe;
				//p($post);
				if (defined('POST_ID') == false) {
					define('POST_ID', $post['id']);
				}

				if (defined('PAGE_ID') == false) {
					define('PAGE_ID', $page['id']);
				}
			}
		 
		if (defined('MAIN_PAGE_ID') == false) {
			$par = get_instance()-> content_model -> getParentPagesIdsForPageIdAndCache($page['id']);
			//p($par );
			$last = end($par);
			// last
			if ($last == 0) {
				$last = $page['id'];
			}
			//p($last );
			define('MAIN_PAGE_ID', $last);
		}

		if (defined('HOME_PAGE_ID') == false) {
			$pageh = get_instance()-> content_model -> getContentHomepage();
			//p($par );

			$last = $pageh['id'];

			//p($last );
			define('HOME_PAGE_ID', $last);
		}

		if (empty($post)) {
			$content = $page;
		} else {
			$post['content_body'] = html_entity_decode($post['content_body']);

		}

		$page['content_body'] = html_entity_decode($page['content_body']);

		if (user_id() != false) {
			//$full_page = get_page ( $page ['id'] );
			$more = get_instance()-> core_model -> getCustomFields('table_content', $page['id']);
			$page['custom_fields'] = $more;
			//p($page);
			if (trim($more["logged_redirect"]) != '') {
				//redirect ( $more ["logged_redirect"] );
				//ob_start();
				get_instance()-> load -> helper('url');
				$redir = site_url($more["logged_redirect"]);
				//p($redir);
				//header ( "HTTP/1.1 301 Moved Permanently" );
				//header ( "Location: " . $redir );
				//redirect ( $redir, 'refresh' );
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . "{$redir}\" />";
				//ob_end_flush(); //now the headers are sent
				exit();
			}

		}

		if ($_POST['format'] == 'json') {
			$output_format = 'json';
		}

		if ($output_format == 'json') {
			$json = array();
			$json['page'] = $page;
			$json['post'] = $post;
			$json = get_instance()-> content_model -> applyGlobalTemplateReplaceables($json);
			$json = json_encode($json);

			exit($json);
		}

		if ($page['content_type'] == 'post') {
			require (APPPATH . 'controllers/advanced/index/display_post_as_page.php');
		}

		if ($page['content_type'] == 'page') {
			if (defined('PAGE_ID') == false) {
				define('PAGE_ID', $page['id']);
			}

			require (APPPATH . 'controllers/advanced/index/display_page.php');
		}

		//print '</pre>';

		$global_template_replaceables = array();
		$global_template_replaceables["content_meta_title"] = $content['content_title'];
		$global_template_replaceables["content_meta_title"] = ($content['content_meta_title'] != '') ? $content['content_meta_title'] : get_instance()-> core_model -> optionsGetByKey('content_meta_title');
		$global_template_replaceables["content_meta_description"] = ($content['content_meta_description'] != '') ? $content['content_meta_description'] : get_instance()-> core_model -> optionsGetByKey('content_meta_description');
		$global_template_replaceables["content_meta_keywords"] = ($content['content_meta_keywords'] != '') ? $content['content_meta_keywords'] : get_instance()-> core_model -> optionsGetByKey('content_meta_keywords');
		$global_template_replaceables["content_meta_other_code"] = ($content['content_meta_other_code'] != '') ? $content['content_meta_other_code'] : get_instance()-> core_model -> optionsGetByKey('content_meta_other_code');
		$global_template_replaceables["content_meta_other_code"] = htmlspecialchars_decode($global_template_replaceables["content_meta_other_code"], ENT_QUOTES);
		$global_template_replaceables["content_meta_other_code"] = html_entity_decode($global_template_replaceables["content_meta_other_code"]);

		$the_active_site_template_dir = TEMPLATES_DIR;
		if (is_dir($the_active_site_template_dir) == false) {

			header("HTTP/1.1 500 Internal Server Error");

			show_error('No such template: ' . $the_active_site_template);

			exit();

		}

		if (trim($content['content_filename']) != '') {

			if (is_readable($the_active_site_template_dir . $content['content_filename']) == true) {

				get_instance()-> load -> vars(get_instance()-> template);

				$content_filename_pre = get_instance()-> load -> file($the_active_site_template_dir . $content['content_filename'], true);

				get_instance()-> load -> vars(get_instance()-> template);

			}

		}

		if (trim($post['content_filename']) != '') {

			if (is_readable($the_active_site_template_dir . $post['content_filename']) == true) {

				get_instance()-> load -> vars(get_instance()-> template);

				$content_from_filename_post = get_instance()-> load -> file($the_active_site_template_dir . $post['content_filename'], true);

				get_instance()-> load -> vars(get_instance()-> template);

			}

		}
		if ($f_editable_layout == false) {
			$d1 = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editabe' . DIRECTORY_SEPARATOR;
			$f_editable_layout1 = $d1 . $content['id'] . '.php';
			if (is_file($f_editable_layout1)) {
				$f_editable_layout = $f_editable_layout1;

			} else {
				$f_editable_layout = false;
			}

		}
		//p($f_editable_layout);
		//if ( empty ( $subdomain_user )) {
		if (strtolower($content['content_layout_file']) == 'inherit') {
			$content['content_layout_file'] = '';
		}

		if ($content['content_layout_file'] != '') {

			//$this->template ['title'] = 'adasdsad';
			if (is_file($the_active_site_template_dir . 'layouts/' . $content['content_layout_file']) == true) {

				get_instance()-> load -> vars(get_instance()-> template);

				$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/' . $content['content_layout_file'], true);

			} elseif (is_file($the_active_site_template_dir . 'layouts/' . 'default_layout.php') == true) {

				get_instance()-> load -> vars(get_instance()-> template);

				$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/' . 'default_layout.php', true);

			} else {

				if (is_file($the_active_site_template_dir . 'layouts/default/index.php') == true) {

					get_instance()-> load -> vars(get_instance()-> template);

					$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/default/index.php', true);

				} elseif (is_file($the_active_site_template_dir . '' . 'default_layout.php') == true) {

					get_instance()-> load -> vars(get_instance()-> template);

					$layout = get_instance()-> load -> file($the_active_site_template_dir . '' . 'default_layout.php', true);

				} elseif (is_file($the_active_site_template_dir . '' . 'layout.php') == true) {

					get_instance()-> load -> vars(get_instance()-> template);

					$layout = get_instance()-> load -> file($the_active_site_template_dir . '' . 'layout.php', true);

				} else {

					header("HTTP/1.1 500 Internal Server Error");

					show_error("Layout file {$content ['content_layout_file']} is not readable or doesn't exist in the templates directory!");

					exit();

				}

			}

		} else {
			$skip_layout_load1 = false;
			$is_saved_layout_from_editor_loaded = false;
			if ($content['content_type'] == 'page') {

				if ($content['content_layout_file'] == '') {
					$use_the_parent_page_layout = false;
					$parent_pages = get_instance()-> content_model -> getParentPagesIdsForPageIdAndCache($content['id']);
					$is_saved_layout_from_editor = false;
					if (!empty($parent_pages)) {
						foreach ($parent_pages as $parent_page) {

							/*$is_saved_layout_from_editor = cf_val ( $parent_page, 'layout' );
							 if ($is_saved_layout_from_editor != false and $is_saved_layout_from_editor != '' and $is_saved_layout_from_editor_loaded == false) {
							 //	$is_saved_layout_from_editor = $this->template_model->parseMicrwoberTags ( $is_saved_layout_from_editor );
							 $is_saved_layout_from_editor = html_entity_decode ( $is_saved_layout_from_editor );
							 $s1 = 'rel="page" page="' . $parent_page . '"';
							 $s2 = 'rel="page" page="' . PAGE_ID . '"';

							 //	var_dump($s1,$s2);
							 $is_saved_layout_from_editor = str_replace ( $s1, $s2, $is_saved_layout_from_editor );

							 $s1 = 'page="' . $parent_page . '"';
							 $s2 = 'page="' . PAGE_ID . '"';
							 $is_saved_layout_from_editor = str_replace ( $s1, $s2, $is_saved_layout_from_editor );

							 //		$is_saved_layout_from_editor = $this->template_model->parseMicrwoberTags ( $is_saved_layout_from_editor );
							 $is_saved_layout_from_editor_loaded = true;
							 $par_id_replace_layout = $parent_page;

							 //var_dump($is_saved_layout_from_editor);
							 }*/

							if ($use_the_parent_page_layout == false) {
								$parent_page_info = get_instance()-> content_model -> contentGetByIdAndCache($parent_page);

								if (strval($parent_page_info['content_layout_file']) != '') {
									if (is_file($the_active_site_template_dir . $parent_page_info['content_layout_file']) == true) {
										$use_the_parent_page_layout = $parent_page_info['content_layout_file'];
									}
								}

								if ($f_editable_layout == false) {
									$d1 = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editabe' . DIRECTORY_SEPARATOR;
									$f_editable_layout = $d1 . $parent_page_info['id'] . '.php';
								}
								if (strval($parent_page_info['content_layout_name']) != '') {
									//	if (is_file ( $the_active_site_template_dir . $parent_page_info ['content_layout_file'] ) == true) {
									$use_the_parent_page_layout_name = $parent_page_info['content_layout_name'];
									$skip_layout_load1 = true;
									$content['content_layout_name'] = $parent_page_info['content_layout_name'];

									//}

								}

							}

						}
					}

					if ($skip_layout_load1 == false) {

						if (is_file($the_active_site_template_dir . 'layouts/' . $use_the_parent_page_layout) == true) {

							get_instance()-> load -> vars(get_instance()-> template);

							$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/' . $use_the_parent_page_layout, true);

						}
						if (strval($layout == '')) {
							if (is_file($the_active_site_template_dir . 'layouts/' . 'default_layout.php') == true) {

								get_instance()-> load -> vars(get_instance()-> template);

								$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/' . 'default_layout.php', true);

							} else {

								if (is_file($the_active_site_template_dir . 'layouts/default/index.php') == true) {

									get_instance()-> load -> vars(get_instance()-> template);

									$layout = get_instance()-> load -> file($the_active_site_template_dir . 'layouts/default/index.php', true);

								} elseif (is_file($the_active_site_template_dir . '' . 'default_layout.php') == true) {

									get_instance()-> load -> vars(get_instance()-> template);

									$layout = get_instance()-> load -> file($the_active_site_template_dir . '' . 'default_layout.php', true);

								} elseif (is_file($the_active_site_template_dir . '' . 'layout.php') == true) {

									get_instance()-> load -> vars(get_instance()-> template);

									$layout = get_instance()-> load -> file($the_active_site_template_dir . '' . 'layout.php', true);

								} else {

									header("HTTP/1.1 500 Internal Server Error");

									show_error("Layout file {$content ['content_layout_file']} is not readable or doesn't exist in the templates directory!");

									exit();
								}
							}

						}

					}

				}

			}

		}

		if ($content['content_layout_name'] != '') {
			get_instance()-> template['content'] = $content;

			$layout_dir = TEMPLATES_DIR . 'layouts/' . $content['content_layout_name'] . '/';
			get_instance()-> template['layout_dir'] = $layout_dir;
			get_instance()-> template['layout_url'] = reduce_double_slashes(dirToURL($layout_dir) . '/');
			get_instance()-> load -> vars(get_instance()-> template);
			$layout = TEMPLATES_DIR . 'layouts/' . $content['content_layout_name'] . '/index.php';

			$layout = get_instance()-> load -> file($layout, true);
			get_instance()-> load -> model('Template_model', 'template_model');
			$layout = get_instance()-> template_model -> parseMicrwoberTags($layout);

			//
		}

		//	p($page);

		//if ($content ['content_layout_file'] == '') {

		//}
		//} else {
		//	$this->load->vars ( $this->template );

		//	$layout = $this->load->file ( $the_active_site_template_dir . 'affiliate_site_1/default_layout.php', true );
		//}

		if (trim($content['content_filename']) != '') {
			if (is_readable($the_active_site_template_dir . $content['content_filename']) == true) {
				get_instance()-> load -> vars(get_instance()-> template);

				//$content_filename = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );
				//$layout = str_ireplace ( '{content}', $content_filename, $layout );
				$layout = str_ireplace('{content}', $content_filename_pre, $layout);

			}

		}
		//p ( $f_editable_layout );
		if ($f_editable_layout != false) {
			//	$layout = str_replace ( '{layout}', $is_saved_layout_from_editor, $layout );
			if (is_file($f_editable_layout)) {
				get_instance()-> load -> vars(get_instance()-> template);

				$f_editable_layout_load = get_instance()-> load -> file($f_editable_layout, true);
				//	p($f_editable_layout_load);
				$html_to_save = $layout;
				$html = str_get_html($html_to_save);
				foreach ($html->find ( 'div[rel="layout"]' ) as $checkbox) {
					//p ( $checkbox->outertext );
					// $checkbox->outertext =$f_editable_layout_load;
					$checkbox -> innertext = $f_editable_layout_load;
					$html -> save();

				}

				$layout = $html -> save();
				//

				//$layout = str_replace ( $s1, $s2, $layout );

				//p ( $layout,1);
				$layout = get_instance()-> template_model -> parseMicrwoberTags($layout);
			}

		} else {
			//$layout = str_replace ( '{layout}', '', $layout );
		}

		if ($content['content_body_filename'] == false) {
			if ($post['content_body_filename'] != false) {
				$content['content_body_filename'] = $post['content_body_filename'];
			}
		}
		//var_dump($post ['content_body_filename']);
		if ($content['content_body_filename'] != false) {
			if (trim($content['content_body_filename']) != '') {
				//$the_active_site_template12 = $this->core_model->optionsGetByKey ( 'curent_template' );
				//$the_active_site_template_dir1 = TEMPLATEFILES . $the_active_site_template12 . '/content_files/';

				$the_active_site_template_dir1 = TEMPLATE_DIR;

				if (is_file($the_active_site_template_dir1 . $content['content_body_filename']) == true) { {
						//$v1 = file_get_contents ( $the_active_site_template_dir . $content ['content_body_filename'] );
						//$v1 = html_entity_decode ( $v1 );
						get_instance()-> load -> vars(get_instance()-> template);
						$content_filename1 = get_instance()-> load -> file($the_active_site_template_dir1 . $content['content_body_filename'], true);

						//print($content ['content_body']);
						$layout = str_ireplace('{content}', $content_filename1, $layout);
						$layout = str_ireplace('{content_body_filename}', $content_filename1, $layout);

						//$v = htmlspecialchars_decode ( $v );
					}
				}

			}

		} else {

			if (trim($content['content_body']) != '') {

				get_instance()-> load -> vars(get_instance()-> template);

				//print($content ['content_body']);
				$layout = str_ireplace('{content}', $content['the_content_body'], $layout);

			}
		}

		if (trim($taxonomy_data) != '') {

			get_instance()-> load -> vars(get_instance()-> template);

			$layout = str_ireplace('{content}', $taxonomy_data, $layout);

		}

		if (trim($content_from_filename_post) != '') {

			//var_dump($content_from_filename_post);
			get_instance()-> load -> vars(get_instance()-> template);

			$layout = str_ireplace('{post_content}', $content_from_filename_post, $layout);

		}

		//just remove it if its still there
		get_instance()-> load -> vars(get_instance()-> template);

		$content = str_ireplace('{content}', '', $content);
		//var_dump($global_template_replaceables);
		//

		//	p(array_size($this->core_model->cache_storage));

		$layout = get_instance()-> content_model -> applyGlobalTemplateReplaceables($layout, $global_template_replaceables);

		//$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables );
		//	var_dump ( $taxonomy_tree );
		$layout = get_instance()-> template_model -> replaceTemplateTags($layout);
		//var_dump ( $taxonomy_tree );
		$opts = array();
		$opts['no_microwber_tags'] = true;
		$opts['no_remove_div'] = true;

		$layout = str_ireplace('{content}', '', $layout);

		if (is_file(ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php')) {

			include_once ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php';

		}

		//	$stats_js = CI::model ( 'stats' )->get_js_code ();

		$layout = get_instance()-> template_model -> parseMicrwoberTags($layout);

		//$layout = CI::model('template')->parseMicrwoberTags($layout);
		//	$layout = CI::model('template')->parseMicrwoberTags($layout);

		$editmode = get_instance()-> session -> userdata('editmode');

		// DISABLING EDITMODE till its finished
		//	$editmode = false;

		if ($editmode == true) {
			$is_admin = is_admin();
			if ($is_admin == true) {
				//$layout = $this->template_model->addTransparentBackgroudToFlash ( $layout );
				$layout_toolbar = get_instance()-> load -> view('admin/toolbar', true, true);
				if ($layout_toolbar != '') {
					$layout = str_replace('</body>', $layout_toolbar . '</body>', $layout);
					$layout = str_replace('</ body>', $layout_toolbar . '</ body>', $layout);
					//some developers put spaces
					$layout = str_replace('</  body>', $layout_toolbar . '</  body>', $layout);
					//some developers put moooore spaces

				}

			}

		}
		$r = (RESOURCES_DIR . 'load.php');
		$r = normalize_path($r, false);
		//$res =$this->load->file ( , true );
		if (is_file($r)) {
			$res = get_instance()-> load -> file($r, true);
			if ($res != false) {

				$layout = str_replace('</ head>', '</head>', $layout);
				//some developers put spaces

				$layout = str_replace('</head>', $res . '</head>', $layout);
			}
		}

		if ($stats_js != false) {
			$layout = str_replace('</body>', $stats_js . '</body>', $layout);
		}

		//<script type="text/javascript" src="http://google.com/jsapi"></script>

		//	$layout = CI::model('template')->parseMicrwoberTags ( $layout, $opts );
		if ($content_display_mode == 'extended_api_with_no_template') {

			$the_user = get_instance()-> session -> userdata('the_user');
			$api_data = $the_user;

			$CI = get_instance();
			return $CI;

		} else {
			if ($cache_page == true) {

				get_instance()-> core_model -> cacheWriteAndEncode($layout, $whole_site_cache_id, $cache_group = 'global');
			}
			//p($this->core_model->cache_storage_hits);
			//p($this->core_model->cache_storage_decoded);
			if (is_file(ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php')) {

				include ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php';

			}

			get_instance()-> output -> set_output($layout);
		}

		//var_dump($_SERVER);

	}
}
?>