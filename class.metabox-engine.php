<?php

/**
 * add_meta_boxes
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
class ssSytemMetaboxEngine
{
	public $nonce = 'ss-metabox-form';
	public $meta_key;
	private $fields;
	private $current_id;
	private $SaveMethodSingle;
	private $stncMBmeta_key_debate = 'ss-setting'; 

	public function __construct($fields, $stncMBmeta_key_debate, $SaveMethodSingle = false)
	{

		$this->meta_key = $stncMBmeta_key_debate;
		$this->SaveMethodSingle = $SaveMethodSingle;

		if (is_admin()) {
			$this->fields = $fields;

			add_action('load-post.php', array($this, 'init_metabox'));
			add_action('load-post-new.php', array($this, 'init_metabox'));
		}
	}

	public function init_metabox()
	{
		// add meta box
		add_action('add_meta_boxes', array(&$this, 'add_custom_meta_box'));

		// metabox save
		add_action('save_post', array(&$this, 'meta_box_save'));
	}


	/**
	 * Save the Meta box values
	 */
	public function meta_box_save($post_id)
	{

		if (wp_is_post_autosave($post_id)) {
			return;
		}

		// Check if not a revision.
		if (wp_is_post_revision($post_id)) {
			return;
		}

		// Stop the script when doing autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		if ($this->SaveMethodSingle) {

			foreach ($this->fields as $fields) {
				foreach ($fields['fields'] as $key => $field) {
					if ($field['type'] == 'gmap') {
						$post_meta_ = isset($_POST[$field['name']]) ? ($_POST[$field['name']]) : '';
						update_post_meta($post_id, $field['name'], $post_meta_);
					} else {
						$post_meta_ = isset($_POST[$field['name']]) ? sanitize_text_field($_POST[$field['name']]) : '';
						update_post_meta($post_id, $field['name'], $post_meta_);
					}
				}
			}
		} else {
			foreach ($this->fields as $fields) {
				foreach ($fields['fields'] as $key => $field) {
					if ($field['type'] == 'gmap') {
						$post_meta_[$field['name']] = isset($_POST[$field['name']]) ? $_POST[$field['name']] : '';
					} else {
						$post_meta_[$field['name']] = isset($_POST[$field['name']]) ? sanitize_text_field($_POST[$field['name']]) : '';
					}
				}
			}
			// Update the meta field in the database.
			update_post_meta($post_id, $this->meta_key, $post_meta_);
		}
	}

	/**
	 * Register the Meta box
	 */
	public function add_custom_meta_box()
	{
		foreach ($this->fields as $key => $field) {
			add_meta_box(
				$field['name'],
				$field['title'],
				array(&$this, 'meta_box_output'),
				$field['page'],
				$field['context'],
				$field['priority'],
				$field
			);
		}
	}

	/**
	 * Output the Meta box
	 */
	public function meta_box_output($post, $field_arg)
	{
		$fields = ($field_arg['args']);
		// wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');
		echo '<input type="hidden" name="' . $this->nonce . '" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<div class="pico ss-metabox-form2" style="' . $fields['style'] . '"  class="' . $fields['class'] . $fields['name'] . '"><section class="grid3">';

		if ($fields['title_h2']) {
			echo '<div><h2  data-required="pageSetting_background_repeat"><strong>' . $fields['title'] . '</strong></h2> </div>';
		}

		foreach ($fields['fields'] as $key => $values) {

			switch ($values['type']) {
				case 'info':
					echo '<div class="' . $values['class_li'] . '"  id="' . $values['name'] . '_li"> <h2><strong>' . $values['title'] . '</strong></h2>  <br>   <div style="padding-left: 15px;">' . $values['description'] . '</div>     <hr>   </div>';
					break;



				case 'select':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
                <select  style="' . $values['style'] . '"  class="' . $values['class'] . '"  name="' . $values['name'] . '" id="' . $values['name'] . '">
                ' . $this->post_options_select($values['values'], $values['name']) . '
                </select>
               ' . $this->post_options_description($values['description']) . '</div>';
					break;



				case 'textarea':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
                 <textarea name="' . $values['name'] . '" class="' . $values['class'] . '" style="' . $values['style'] . '"  id="' . $values['name'] . '" cols="40" rows="6" >' . $this->get_meta($values['name']) . '</textarea>
               ' . $this->post_options_description($values['description']) . '</div>';
					break;

				case 'text':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
              <input type="text" value="' . $this->get_meta($values['name']) . '" class="' . $values['class'] . '" style="' . $values['style'] . '"  name="' . $values['name'] . '" id="' . $values['name'] . '"/>
               ' . $this->post_options_description($values['description']) . '</div>';
					break;

				case 'hidden':
					echo '
				  <input type="hidden" value="' . $this->get_meta($values['name']) . '" class="' . $values['class'] . '" style="' . $values['style'] . '"  name="' . $values['name'] . '" id="' . $values['name'] . '"/>
				   ';
					break;


				case 'checkbox':
					echo '<div id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
              <input type="checkbox" value="on"  ' . $this->post_options_checked($values['name']) . '   class="' . $values['class'] . '" style="' . $values['style'] . '"  name="' . $values['name'] . '" id="' . $values['name'] . '"/>
               ' . $this->post_options_description($values['description']) . '</div>';
					break;




				case 'radio':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '"><fieldset><legend class="' . $values['class'] . '" id="' . $values['name'] . '_li"><strong>' . $values['title'] . '</strong></legend>';
					foreach ($values['values'] as $key => $value) {
						echo '<label for="' . $values['name'] . $key . '">';
						echo ' <input id="' . $values['name'] . $key . '" type="radio" value="' . $key . '"  ' . $this->post_options_radio($values['name'], $key) . '    name="' . $values['name'] . '" />';
						echo $value;
						echo '</label>';
					}
					echo $this->post_options_description($values['description']) . '</div></fieldset>';
					break;

		
					case 'switch':
						echo '<div id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
				  <input type="checkbox" role="switch" value="on"  ' . $this->post_options_checked($values['name']) . '   class="' . $values['class'] . '" style="' . $values['style'] . '"  name="' . $values['name'] . '" id="' . $values['name'] . '"/>
				   ' . $this->post_options_description($values['description']) . '</div>';
						break;


				case 'color':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li">
					<label for="' . $values['name'] . '">' . $values['title'] . '</label>
              <input type="color" value="' . $this->get_meta($values['name']) . '"  class="' . $values['class'] . '" style="' . $values['style'] .'" name="' . $values['name'] . '" id="' . $values['name'] . '"/>
               ' . $this->post_options_description($values['description']) . '</div>';
					break;


					case 'date':
						echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li">
						<label for="' . $values['name'] . '">' . $values['title'] . '</label>
				  <input type="date" value="' . $this->get_meta($values['name']) . '"  class="' . $values['class'] . '" style="' . $values['style'] . '" name="' . $values['name'] . '" id="' . $values['name'] . '"/>
				   ' . $this->post_options_description($values['description']) . '</div>';
						break;



					//TODO: future #important
				/*UPLOAD */
				case 'upload':
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li"><label for="' . $values['name'] . '">' . $values['title'] . '</label>
              <input type="text" value="' . $this->get_meta($values['name']) . '"  class="' . $values['class'] . '" style="display:none;' . $values['style'] . '" name="' . $values['name'] . '" id="' . $values['name'] . '"/>
              <input  id="' . $values['name'] . '_extra"   class="page_upload_trigger_element button button-primary button-large" name="' . $values['name'] . '_extra" type="button" value="' . $values['button_text'] . '" />
        ' . $this->post_options_description($values['description']) . '
        <br>
        <div class="background_attachment_metabox_container">';
					if (!empty($this->get_meta($values['name']))) {
						$fileExtension = $this->fileExtension($this->get_meta($values['name']));
						if ($fileExtension == "jpg" || $fileExtension == "jpeg" || $fileExtension == "png" || $fileExtension == "gif") {
							echo '<div class="images-containerBG"><div class="single-imageBG"><div class="delete">X</div>';
							echo '  <img  data-targetID="' . $values['name'] . '" alt="' . $values['name'] . '" class="attachment-100x100 wp-post-image" witdh="100" height="100" src="' . $this->get_meta($values['name']) . '">';
							echo '</div></div>';
						} else {
							?>
							<div class="images-containerBG">
								<div style="width: 53px; height: 53px;" class="single-imageBG">
									<div data-targetID="<?php echo $values['name'] ?>" class="delete_media">X</div>
									<div style="font-size: 46px" class="info dashicons dashicons-admin-media"></div>
								</div>
							</div>
							<?php
						}
					}
					echo '</div></div>';
					break;

				// Media Gallery Code 		//TODO: future #important
				case 'media-gallery':
					$imagewow2 = array();
					$imagesID = array();
					$pictures = "";
					$imagesBUll_ = $this->get_meta($values['name']);
					if (!empty($imagesBUll_)) {
						$imagesBUlls = explode(',', $imagesBUll_);
						$imagesBUlls = array_unique($imagesBUlls);

						foreach ($imagesBUlls as $key => $val) {
							if ($val == '') {
								unset($imagesBUlls[$key]);
							}
						}
					}
					if (!empty($imagesBUlls)):
						foreach ($imagesBUlls as $imagesBUll):
							$imagewow = wp_get_attachment_image_src(($imagesBUll), 'thumb');
							$imagewow2[] = $imagewow[0];
							$imagesID[] = $imagesBUll;
							$pictures .= '<div class="single-image" ><div class="delete"> X</div ><img data-id="' . $imagesBUll . '" src="' . $imagewow[0] . '" alt="sd"/></div >';
						endforeach;
					endif;
					echo '<div class="' . $values['class_li'] . '" id="' . $values['name'] . '_li">
                                <div class="drop_meta_item gallery">
	                            <label for="' . $values['name'] . '">' . $values['title'] . '</label>
	                            <div class="st_studio-metadata">
	                            <div class="images-container">' . $pictures . '</div>
	                            <div class="images-container2">
                                <input id="' . $values['name'] . '"   class="meta_field media_field_content"  data-value="' . implode(',', $imagewow2) . '"  value="' . implode(',', $imagesID) . '" 
                                  name="' . $values['name'] . '" type="hidden"   style="' . $values['style'] . '"/>
	                            <input type="button" name="uploader" class="ss-stytem-upload-button button button-primary" value="' . __('Add Images', 'tvs_wp_debate') . '">
	                            </div>
	                            </div>
	                            </div>
	                      </div>';
					break;
			}
		}
		echo '  </section>
    </div>';
	}


	private function fileExtension($file_name)
	{
		return preg_replace('/^.*\.([^.]+)$/D', '$1', $file_name);
	}






	/**
	 * function to return a custom field value.
	 */
	public function get_meta($value)
	{

		if ($this->SaveMethodSingle) {
			return $this->get_metaSingle($value);
		} else {
			return $this->get_metaSerialize($value);
		}

	}

	/**
	 * function to return a custom field value.
	 */
	private function get_metaSerialize($value)
	{
		global $post;
		$field = get_post_meta($post->ID, $this->meta_key, true);
		if (!empty($field)) {
			if (array_key_exists($value, $field)) {
				$field = $field[$value];
			}
		}
		if (!empty($field)) {
			return is_array($field) ? stripslashes_deep($field) : stripslashes(wp_kses_decode_entities($field));
		} else {
			return false;
		}
	}


	/**
	 * function to return a custom field value.
	 */
	private function get_metaSingle($value)
	{
		global $post;
		return get_post_meta($post->ID, $value, true);
	}


	/**
	 * engine descripton
	 */
	private function post_options_description($value)
	{
		if ($value != '') {
			return '<div class="form_hint">' . $value . '</div>';
		} else {
			return '';
		}
	}

	/**
	 * for select list serialize data
	 * engine select option list
	 */
	private function post_options_selectSerialize($arrays, $name)
	{
		$out = '';
		foreach ($arrays as $key => $option) {
			$meta = $this->get_metaSerialize($name);
			if ($meta == $key) {
				$out .= '<option  value="' . $key . '" selected="selected"> ' . $option . '</option>';
			} else {
				$out .= '<option value="' . $key . '" > ' . $option . '</option>';
			}
		}
		return $out;
	}

	/**
	 * engine select option list
	 */
	private function post_options_select($arrays, $name)
	{
		$out = '';
		foreach ($arrays as $key => $option) {
			$meta = $this->get_meta($name);
			if ($meta == $key) {
				$out .= '<option  value="' . $key . '" selected="selected">' . $option . '</option>';
			} else {
				$out .= '<option value="' . $key . '" > ' . $option . '</option>';
			}
		}

		return $out;
	}


	/**
	 * engine checked
	 */
	private function post_options_checked($value)
	{
		return $this->get_meta($value) === "on" ? "checked" : "";
	}

	/**
	 * engine checked
	 */
	private function post_options_radio($id, $value)
	{
		// print_r( $id);
		// var_dump( $this->get_meta($id));
		// var_dump( $value);
		// //  (string) "name"//
		return $this->get_meta($id) === (string) $value ? "checked" : "";
	}
}
