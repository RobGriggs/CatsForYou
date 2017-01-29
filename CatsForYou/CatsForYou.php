<?php
/**
Plugin Name: Cats For You
Description: Plugin that, for a configured user, replaces images with random pictures of cats in silly costumes. Config menu Location: Users -> Configure Cats
Version: 1.0

Author: Rob Griggs
Author URI: www.robgriggs.com
Date: 1.27.17

**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('CFY_FILE', __FILE__);
define('CFY_PATH', plugin_dir_path(__FILE__));

new CatsForYou();


class CatsForYou
{
	//key to config data stored
	protected $option_name = 'cfy_options';
	//data key values to be stored, value as default configuration
	protected $dataBits = array( 'target_user_email' => 'email'); 
	
	public function __construct()
	{
		add_action('admin_init', array($this, 'activate'));
		add_action('admin_menu', array($this, 'plugin_menu'));
		add_action('init', array($this, 'dispatch_cats'));
	}

	function plugin_menu()
	{
			add_submenu_page('users.php', 'Apply Cats', 'Configure Cats', 'manage_options', 'Aiming Cats', array($this, 'menu_options' ));
	}

	function dispatch_cats()
	{
		$currentUser = wp_get_current_user()->user_email;
		$targetUser = get_option('cfy_target');			

		if($currentUser == $targetUser){
			wp_register_script('cat_js_init', plugins_url("js/cats.js", __FILE__), array('jquery'), '', true);
			wp_enqueue_script('cat_js_init');		
			
			wp_deregister_script('jquery');
			wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
			wp_enqueue_script('jquery');
		}
	}

	function jQuery_init()
	{
		wp_enqueue_scripts('jQuery_init', CFY_PATH."js/jQuery.js");
	}

	function menu_options()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?>

		<h2>Cats for Who?</h2>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php settings_fields( 'cfy_settings' ); ?>
    			<?php do_settings_sections( 'cfy_settings' ); ?>
	            <table class="form-table">
	                <tr valign="top"><th scope="row">Target User's Email:</th>
	                 
	                <?php
	                	$option = get_option('cfy_target');
	                	echo '<td><input type="text" name="cfy_target" value="'.$option.'" /></td>';
	                ?>
	                </tr>
	            </table>
				<?php @submit_button(); ?>
	        </form>
	        <h2>Handy Dandy Email Reference (site admins)</h2>
			<p>
			<?php
			$args = array('role' => 'administrator');
			$admins = get_users($args);

			$suggestions = '';
			
			//QUICK AND DIRTY DEBUG
			//echo CFY_PATH.'<br>';

			/**
			$currentUser = wp_get_current_user();
			if(get_option('cfy_target') == $currentUser->user_email){
				echo 'target successfully aquired';
			}
			**/

			//echo CFY_PATH."js/cats.js";

			//echo get_option('cfy_target').'<br>';

			echo '<hr>';

			foreach($admins as $key=>$values){
				$suggestions .= $values->data->display_name.': '.$values->data->user_email.'<br>';
			}

			
			echo $suggestions;

			//echo '<pre>';
			//print_r($admins);
			?>	
			</p>
	        
		</div>
	<?php
	}

	public function activate() {
		register_setting('cfy_settings', 'cfy_target');
  		add_option('cfy_target', $this->dataBits['target_user_email']);
	}

	public function deactivate() {
    	delete_option($this->dataBits['target_user_email']);
	}
	
}
?>