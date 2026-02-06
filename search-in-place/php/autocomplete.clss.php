<?php
/* Prevent direct access */
defined( 'ABSPATH' ) || die( "You can't access this file directly." );

if ( ! class_exists( 'CPSPAutocomplete' ) ) {
	class CPSPAutocomplete {

		private $_settings;
		private $_defaults;

		public function __construct() {
			$lang            = get_locale();
			$this->_defaults = array(
				'enabled' => true,
				'method'  => 'google',
				'count'   => 10,
				'chars'   => 25,
				'lang'    => substr( $lang, 0, 2 ),
				'url'     => 'http://suggestqueries.google.com/complete/search?output=toolbar&oe=utf-8&client=toolbar',
				'start'   => false,
			);
		} // End __construct

		/**** PRIVATE METHODS ****/

		private function _getSettings() {
			if ( empty( $this->_settings ) ) {
				$this->_settings = get_option( 'CPSPAutocomplete', array() );
			}
			return $this->_settings;
		} // End _getSettings

		private function _substrAtWord( $text, $length ) {
			if ( strlen( $text ) <= $length ) {
				return $text;
			}
			$blogCharset = get_bloginfo( 'charset' );
			$charset     = '' !== $blogCharset ? $blogCharset : 'UTF-8';
			$s           = mb_substr( $text, 0, $length, $charset );
			return mb_substr( $s, 0, strrpos( $s, ' ' ), $charset );
		} // End _substrAtWord

		/**** PUBLIC METHODS ****/

		public function clearSettings() {
			delete_option( 'CPSPAutocomplete' );
		} // End clearSettings

		public function updateSettings( $settings ) {
			$this->_getSettings();

			$this->setAttr( 'enabled', isset( $settings['autocomplete'] ) ? true : false );
			$this->setAttr( 'method', empty( $settings['autocomplete_method'] ) || $settings['autocomplete_method'] == 'google'  ? 'google' : 'website' );
			$this->setAttr( 'start', isset( $settings['autocomplete_start'] ) ? true : false );
			if ( isset( $settings['autocomplete_chars'] ) ) {
				$this->setAttr( 'chars', is_numeric( $settings['autocomplete_chars'] ) ? intval( $settings['autocomplete_chars'] ) : 0 );
			}
			if ( isset( $settings['autocomplete_count'] ) ) {
				$this->setAttr( 'count', is_numeric( $settings['autocomplete_count'] ) ? intval( $settings['autocomplete_count'] ) : 0 );
			}

			update_option( 'CPSPAutocomplete', $this->_settings );
		} // updateSettings

		public function setAttr( $attr, $val ) {
			$this->_getSettings();
			$this->_settings[ $attr ] = $val;
		} // setAttr

		public function getSettings() {
			echo '
			<style>
				.website-method{display:' . ( $this->getAttr( 'method' ) == 'website' ? 'table-row' : 'none'  ) . ';}
				.google-method{display:' . ( $this->getAttr( 'method' ) == 'google' ? 'table-row' : 'none'  ) . ';}
			</style>
			<script>
				jQuery(document).on("change", \'[name="autocomplete_method"]\', function(){
					let m = jQuery(\'[name="autocomplete_method"]:checked\').val();
					if(m == "google") {
						jQuery(".google-method").css("display", "table-row");
						jQuery(".website-method").hide();
					} else {
						jQuery(".website-method").css("display", "table-row");
						jQuery(".google-method").hide();
					}
				});
			</script>
			<div class="postbox search-in-place-postbox">
				<h3 class="hndle"><span>' . esc_html( __( 'Autocomplete', 'search-in-place' ) ) . '</span></h3>
				<div class="inside">

					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="autocomplete">' . esc_html( __( 'Enabling autocomplete', 'search-in-place' ) ) . '</label>
								</th>
								<td>
									<input type="checkbox" name="autocomplete" id="autocomplete" value="1" ' . ( ( $this->getAttr( 'enabled' ) ) ? 'checked' : '' ) . ' />
									<div style="font-weight:600;margin-top:30px;margin-bottom:15px;">' . esc_html__( 'Get terms suggestion from', 'search-in-place' ) . '</div>
									<div style="display:flex; align-items:center; gap:20px;">
										<div><label><input type="radio" name="autocomplete_method" value="google" ' . ( $this->getAttr( 'method' ) == 'google' ? 'checked' : '' ) . '> Google</label></div>
										<div><label><input type="radio" name="autocomplete_method" value="website" ' . ( $this->getAttr( 'method' ) == 'website' ? 'checked' : '' ) . '> Website (experimental)</label></div>
									</div>
								</td>
							</tr>
							<tr valign="top" class="website-method">
								<td colspan="2">
									<p style="border:1px solid #4caf50;margin-bottom:10px;padding:5px;background-color: #e6f5e6;">
										' . esc_html__( "The Website alternative is still experimental, as it can be influenced by the structure of the website. It extracts term suggestions directly from the website's information, including page and post titles, content, and excerpts.", 'search-in-place' ) . '
									</p>
								</td>
							</tr>
							<tr valign="top" class="google-method">
								<td colspan="2">
									<p style="border:1px solid #4caf50;margin-bottom:10px;padding:5px;background-color: #e6f5e6;">
										' . esc_html__( "The Google alternative utilizes the \"Google Suggest Query\" API to retrieve term suggestions based on Google's search experience. However, the suggested terms may not always be closely related to the specific website.", 'search-in-place' ) . '
									</p>
								</td>
							</tr>
							<tr valign="top" class="google-method">
								<th scope="row">
									<label for="autocomplete_chars">' . esc_html( __( 'Max keyword length', 'search-in-place' ) ) . '</label>
								</th>
								<td>
									<input type="number" name="autocomplete_chars" id="autocomplete_chars" value="' . esc_attr( $this->getAttr( 'chars' ) ) . '" />
								</td>
							</tr>
							<tr valign="top" class="google-method">
								<th scope="row">
									<label for="autocomplete_count">' . esc_html( __( 'Max keywords count', 'search-in-place' ) ) . '</label>
								</th>
								<td>
									<input type="number" name="autocomplete_count" id="autocomplete_count" value="' . esc_attr( $this->getAttr( 'count' ) ) . '" />
								</td>
							</tr>
							<tr valign="top" class="google-method">
								<th scope="row">
									<label for="autocomplete_start">' . esc_html( __( 'Suggest terms starting with the typed text', 'search-in-place' ) ) . '</label>
								</th>
								<td>
									<input type="checkbox" name="autocomplete_start" id="autocomplete_start" value="1" ' . ( $this->getAttr( 'start' ) ? 'CHECKED' : '' ) . ' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			';
		} // getSettings

		public function getAttr( $attr ) {
			$this->_getSettings();
			if ( isset( $this->_settings[ $attr ] ) ) {
				return $this->_settings[ $attr ];
			}
			if ( isset( $this->_defaults[ $attr ] ) ) {
				return $this->_defaults[ $attr ];
			}
		} // getAttr

		private function _fromGoogle( $terms ) {
			$result 	= array(); // Result

			$url      	= $this->getAttr( 'url' ) . '&hl=' . $this->getAttr( 'lang' ) . '&q=' . urlencode( $terms );
			$response 	= wp_remote_get(
				$url,
				array(
					'sslverify'  => false,
					'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
				)
			);

			if ( is_wp_error( $response ) ) {
				error_log( $response->get_error_message(), 0 );
				return $result;
			}

			$body = wp_remote_retrieve_body( $response );
			if ( empty( $body ) ) {
				return $result;
			}

			if ( function_exists( 'mb_convert_encoding' ) ) {
				$body = mb_convert_encoding( $body, 'UTF-8' );
			}
			try {
				$xml = simplexml_load_string( $body );
				if ( empty( $xml ) ) {
					return $result;
				}

				$json  = json_encode( $xml );
				$array = json_decode( $json, true );

				$keywords = array();

				if ( isset( $array['CompleteSuggestion'] ) ) {
					foreach ( $array['CompleteSuggestion'] as $k => $v ) {
						if ( isset( $v['suggestion'] ) ) {
							$keywords[] = $v['suggestion']['@attributes']['data'];
						} elseif ( isset( $v[0] ) ) {
							$keywords[] = $v[0]['@attributes']['data'];
						}
					}
				}

				$m = $this->getAttr( 'count' );
				$c = 0;
				foreach ( $keywords as $k ) {
					$t = strtolower( $k );
					if (
						$t != $terms &&
						( '' != $str = $this->_substrAtWord( $t, $this->getAttr( 'chars' ) ) )
					) {
						$start = $this->getAttr( 'start' );
						if ( ! $start || ( $start && strpos( $t, $terms ) === 0 ) ) {
							$result[] = $str;
							$c++;
						}
					}
					if ( $m <= $c ) {
						break;
					}
				}
			} catch ( Exception $e ) {
				error_log( $e->getMessage() );
			}

			return $result;

		} // _fromGoogle

		private function _fromWebsite( $terms ) {
			global $wpdb;

			$result = [];

			$like = '%' . $wpdb->esc_like($terms) . '%';
			$sql = $wpdb->prepare(
				"SELECT ID, post_title, post_excerpt, post_content
				FROM $wpdb->posts
				WHERE post_status='publish'
				  AND (post_title LIKE %s
				   OR post_excerpt LIKE %s
				   OR post_content LIKE %s)
				LIMIT 50",
				[$like, $like, $like]
			);

			$rows = $wpdb->get_results($sql);

			$extract_suggestion = function ($text, $terms) {
				$pos = mb_stripos($text, $terms);

				if ($pos === false) {
					return null;
				}

				$after = mb_substr($text, $pos + mb_strlen($terms));

				// Normalize multiple spaces
				$after = preg_replace('/\s+/u', ' ', $after);

				if ($after === '') {
					return null;
				}

				$firstChar = mb_substr($after, 0, 1);

				// Case 1: incomplete word (next char is a letter or number)
				if (preg_match('/[\p{L}\p{N}]/u', $firstChar)) {
					// return the rest of the word until next space
					if (preg_match('/^([\p{L}\p{N}]+)/u', $after, $m)) {
						return $m[1];
					}
				}

				// Case 2: complete word
				// Remove tags from beginning
				$after = ltrim($after);
				$after = preg_replace('/<[^>]*>/', '', $after); // remove tags safely
				$after = ltrim($after);

				if (preg_match('/^([\p{L}\p{N}]+)/u', $after, $m)) {
					return ' ' . $m[1];
				}

				return null;
			};

			foreach ($rows as $row) {
				$flag = false;
				foreach (['post_title', 'post_excerpt', 'post_content'] as $field) {
					$raw = $row->$field;
					$s = $extract_suggestion($raw, $terms);
					if ($s) {
						$result[] 	= $terms . $s;
						$flag 		= true;
						break;
					}
				}
				if ( $flag ) break;
			}

			return $result;
		} // _fromWebsite

		public function autocomplete( $terms = '' ) {
			$result = []; // Result

			if ( empty( $terms ) || ! $this->getAttr( 'enabled' ) ) {
				return $result;
			}

			if ( $this->getAttr('method') == 'google' ) {
				$result = $this->_fromGoogle( $terms );
			} else {
				$result = $this->_fromWebsite( $terms );
			}

			return $result;

		} // autocomplete
	} // End CPSPAutocomplete
}
