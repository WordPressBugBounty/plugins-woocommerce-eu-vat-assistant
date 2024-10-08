<?php
namespace Aelia\WC;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

if(!class_exists('Aelia\WC\Message')) {
	/**
	 * Class to represent messages generated by the plugin.
	 */
	class Message {
		public $level;
		public $code;
		public $message;
		public $dismissable;
		public $sender_id;
		public $sender_name;
		public $permissions;
		// @since 2.4.12.230803
		public $message_header;

		/**
		 * Message headers display on top of message lists.
		 *
		 * @var array
		 * @since 1.6.9.151103
		 */
		protected static $message_headers = array(
			// Message headers are populated inside the constructor
		);

		/**
		 * A map of message level => CSS class. Used to format messages during rendering.
		 *
		 * @var array
		 * @since 1.6.9.151103
		 */
		protected static $message_css_classes = array(
			E_USER_ERROR => 'error',
			E_USER_WARNING => 'updated warning', // "updated" is the WordPress style that shows update messages and warnings
			E_USER_NOTICE => 'updated',
			E_ERROR => 'error',
			E_WARNING => 'updated warning', // "updated" is the WordPress style that shows update messages and warnings
			E_NOTICE => 'updated',
		);

		/**
		 * The default parameters used to generate a message.
		 *
		 * @var array
		 * @since 1.6.9.151103
		 */
		protected static $default_params = array(
			'level' => E_USER_NOTICE,
			'code' => '',
			'dismissable' => false,
			'sender_id' => Definitions::PLUGIN_SLUG,
			'permissions' => array(),
		);

		/**
		 * Initialises the message headers.
		 *
		 * @since 1.6.9.151103
		 */
		public static function init_message_headers() {
			self::$message_headers = array(
				E_USER_ERROR => __('Error'),
				E_USER_WARNING => __('Warning'),
				E_USER_NOTICE => __('Notice'),
				E_ERROR => __('Error'),
				E_WARNING => __('Warning'),
				E_NOTICE => __('Notice'),
			);
		}

		/**
		 * Returns the header to be displayed for a specific message level.
		 *
		 * @param int level The message level.
		 * @return string
		 * @since 1.6.9.151103
		 */
		protected function get_message_header($level) {
			// If a header was specified for this message, use it insteaf of the default
			// one
			if(!empty($this->message_header)) {
				return $this->message_header;
			}

			return isset(self::$message_headers[$level]) ? self::$message_headers[$level] : '';
		}

		/**
		 * Class constructor.
		 *
		 * @param int level The message level.
		 * @param string message The message itself.
		 * @param string code The message code, if any.
		 * @param bool dismissable Indicates if the message can be dismissed.
		 * @param string sender_id The ID of the sender who created the message. Used
		 * to distinguish messages with the same code.
		 * @param array params Additional parameters for the message.
		 */
		public function __construct($level, $message, $code = '', $dismissable = false, array $params = array()) {
			$params = array_merge(static::$default_params, $params);

			$this->level = $level;
			$this->message = $message;
			$this->code = $code;
			$this->dismissable = $dismissable;
			$this->sender_id = $params['sender_id'];

			if(!is_array($params['permissions'])) {
				$params['permissions'] = array($params['permissions']);
			}
			$this->permissions = $params['permissions'];
			$this->message_header = !empty($params['message_header']) ? $params['message_header'] : '';
		}

		/**
		 * Factory method.
		 *
		 * @param string message The message to display.
		 * @param array params An array of parameters used to create the message.
		 * @since 1.6.9.151103
		 */
		public static function factory($message, array $params = array()) {
			$params = array_merge(static::$default_params, $params);
			return new static(
				$params['level'],
				$message,
				$params['code'],
				$params['dismissable'],
				$params
			);
		}

		/**
		 * Builds and return the message ID.
		 *
		 * @return string
		 * @since 1.6.9.151103
		 */
		public function get_message_id() {
			return (string)$this->sender_id . '-' . (string)$this->code;
		}

		/**
		 * Indicates if this message should be displayed to current user. A message
		 * might not have to be displayed due to permissions, or other restrictions.
		 *
		 * @return bool
		 * @since 1.6.9.151103
		 */
		protected function should_show_message() {
			$result = true;

			// Check if the user has the permission to see the message
			if(!empty($this->permissions)) {
				$result = false;
				foreach($this->permissions as $permission) {
					if(current_user_can($permission)) {
						$result = true;
						break;
					}
				}
			}
			return $result;
		}

		/**
		 * Returns the Ajax URL that will trigger the installation of a plugin.
		 *
		 * @param string plugin_name The plugin name.
		 * @return string
		 * @since 1.6.9.151103
		 */
		protected static function get_ajax_url($sender_id, $message_code, $action) {
			$message_id = (string)$sender_id . '-' . (string)$message_code;
			$ajax_args = http_build_query(array(
				'action' => 'aelia_' . $action . '_message',
				Definitions::ARG_MESSAGE_ID => $message_id,
				'_ajax_nonce' => wp_create_nonce('aelia-message-' . $action . '-' . $message_id),
			));
			$ajax_url = admin_url('admin-ajax.php', 'absolute') . '?' . $ajax_args;
			return $ajax_url;
		}

		/**
		 * Renders the message, optionally displaying it.
		 *
		 * @param bool display Indicates if the HTML for message should be displayed,
		 * or only returned.
		 * @return string The HTML to display the message.
		 * @since 1.6.9.151103
		 */
		public function render($display = false) {
			// Check if the message should be displayed before rendering it
			if(!$this->should_show_message()) {
				return '';
			}
			$css_class = isset(self::$message_css_classes[$this->level]) ? self::$message_css_classes[$this->level] : '';

			$message_id = $this->get_message_id();
			$output = '<div id="' . $message_id . '" class="wc_aelia message ' . $css_class . '">';

			$message_header = $this->get_message_header($this->level);
			$message_header .= '<span class="message_id">';
			$message_header .= sprintf(' [%s - %s]',
																 $this->sender_id,
																 (empty($this->code) ? 'no-code-specified' : $this->code));
			$message_header .= '</span>';
			$output .= '<h4 class="message_header">';
			$output .= $message_header;

			// If the message is dismissable, show the action to hide it
			if($this->dismissable) {
				$dismiss_ajax_url = self::get_ajax_url($this->sender_id, $this->code, 'dismiss');
				$output .= '<div class="aelia message_actions">';
				$output .= '<a class="message_action button" href="' . $dismiss_ajax_url . '" message_id="' . $message_id . '">';
				$output .= __('Hide message permanently', WC_AeliaFoundationClasses::$text_domain);
				$output .= '</a>';
				$output .= '</div>';
			}
			$output .= '</h4>';

			$output .= '<p class="wc_aelia message">';
			$output .= $this->message;
			$output .= '</p>';
			$output .= '</div>';

			if($display) {
				echo $output;
			}
			return $output;
		}
	}
	// Initialise the message headers map
	Message::init_message_headers();
}
