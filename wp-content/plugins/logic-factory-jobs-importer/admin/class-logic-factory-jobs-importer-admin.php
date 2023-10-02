<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.haatchmedia.com
 * @since      1.0.0
 *
 * @package    Logic_Factory_Jobs_Importer
 * @subpackage Logic_Factory_Jobs_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Logic_Factory_Jobs_Importer
 * @subpackage Logic_Factory_Jobs_Importer/admin
 * @author     Naveen Verma <hello@haatchmedia.com>
 */
class Logic_Factory_Jobs_Importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		//https://developer.personio.de/reference/post_v1-recruiting-applications
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', 					array( $this, 'nv_admin_menu' ));
		add_action( 'import_jobs',					array( $this, 'import_jobs' ));
		add_action( 'admin_init', 					array( $this, 'nv_settings_init' ));
		add_filter( 'the_content', 					array( $this, 'nv_add_job_details' ), 1); 
		add_action( 'wpcf7_before_send_mail',		array( $this, 'nv_sent_job_submission' ), 10, 3); 
		add_filter( 'wpcf7_mail_components',        array( $this, 'my_cf7_mail_components' ));

		$this->tt_api_url	 					= get_option( 'tt_api_url' );
		$this->tt_api_key						= get_option( 'tt_api_key' );
		$this->tt_api_version					= get_option( 'tt_api_version' );
		$this->tt_api_job_url					= get_option( 'tt_api_job_url' );
		$this->tt_api_bearer					= get_option( 'tt_api_bearer' );
		$this->tt_api_company_id				= get_option( 'tt_api_company_id' );
		$this->tt_api_channel_id				= get_option( 'tt_api_channel_id' );
		$this->tt_api_application_post_url		= get_option( 'tt_api_application_post_url' );
		

	}
	
	function my_cf7_mail_components( $components ){
		
		//$components['additional_headers'] .= "\r\nCc:hello@haatchmedia.com";
		//$components['additional_headers'] .= "\r\nFrom: Developer <hello@developer.com>";
	
		return $components;
		
	}

	function nv_sent_job_submission( $contact_form, &$abort, $submission )
	{
		/*
		India careers form submissions - careers-in@thelogicfactory.com;
		Netherlands careers form submissions - careers-nl@thelogicfactory.com;
		United Kingdom form submissions - careers-uk@thelogicfactory.com;
		United States form submission - careers-us@thelogicfactory.com;
		*/

		$wpcf 	 = WPCF7_ContactForm::get_current();
		$form_id = $contact_form->posted_data['_wpcf7'];
		$post_id = $submission->get_meta('container_post_id');

		$job_data = get_post_meta($post_id, 'job_data', true);
		
		$date = date('d_m_Y_h_i_s');
		
		update_post_meta($post_id, '_submissions_'.$date, $log);

		$taxonomy = 'career-category'; // Replace with the desired taxonomy

		$terms = get_the_terms($post_id, $taxonomy);

		if ($terms && !is_wp_error($terms)) {
			$term_names = array();
			foreach ($terms as $term) {
				$term_names[] = $term->name;
			}
			
			if(in_array("USA", $term_names))
			{
				$recipient = "careers-us@thelogicfactory.com";
			}

			if(in_array("The Netherlands", $term_names))
			{
				$recipient = "careers-nl@thelogicfactory.com";
			}

			if(in_array("United Kingdom", $term_names))
			{
				$recipient = "careers-uk@thelogicfactory.com";
			}

			if(in_array("India", $term_names))
			{
				$recipient = "careers-in@thelogicfactory.com";
			}
			
			if($recipient) 
			{
				$mail = $contact_form->prop( 'mail' );
				$mail['recipient'] = $recipient;
				$mail['headers'] = "From: $submission->get_posted_data( 'Name' ) <$submission->get_posted_data( 'email' )>";
				$contact_form->set_properties(array('mail'=>$mail));
				
			}
		}

		$name  		= $submission->get_posted_data( 'Name' );
		$lastname	= $submission->get_posted_data( 'LastName' );
		$email 		= $submission->get_posted_data( 'email' );
		$message 	= $submission->get_posted_data( 'message' );
		$files		= $submission->uploaded_files();

		if(!is_array($files))
			return;

		if(is_array($files))
		{
			
				$filename 		= $files['Upload'][0];
				$log['sentfiles'][]		= $filename;
				$post_data['file'] 		= curl_file_create($filename, 'application/pdf', time().'.pdf');
				$headers = [
					"Accept: application/json",
					"Content-Type: multipart/form-data",
					"X-Company-ID: ".$this->tt_api_company_id,
					"Authorization: Bearer ".$this->tt_api_bearer
				];

				// configure the CURL request
				$request = curl_init($this->tt_api_application_post_url.'/documents');
				curl_setopt($request, CURLOPT_POST, 1);
				curl_setopt($request, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

				// Execute it 
				$fileresponse 				= curl_exec($request);

				$response 					= json_decode($fileresponse);

				$userfiles[] 				= ["uuid" => $response->uuid, "original_filename" => basename($filename), "category" => "cv"];
			
				curl_close($request);
			
				$filename 		= $files['UploadLetter'][0];
				//$upload_dir   	= wp_upload_dir();
				//$filename		= $upload_dir['basedir'].$filename[1];

				$log['sentfiles'][]		= $filename;
				$post_data['file'] 		= curl_file_create($filename, 'application/pdf', time().'.pdf');
				$headers = [
					"Accept: application/json",
					"Content-Type: multipart/form-data",
					"X-Company-ID: ".$this->tt_api_company_id,
					"Authorization: Bearer ".$this->tt_api_bearer
				];

				// configure the CURL request
				$request = curl_init($this->tt_api_application_post_url.'/documents');
				curl_setopt($request, CURLOPT_POST, 1);
				curl_setopt($request, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

				// Execute it 
				$fileresponse 				= curl_exec($request);

				$response 					= json_decode($fileresponse);

				$userfiles[] 				= ["uuid" => $response->uuid, "original_filename" => basename($filename), "category" => "cv"];

				$log['sentfileslog'][]		= $fileresponse;
			
				curl_close($request);
			
		}
		
		$body = '{
			"job_position_id": 		'.$job_data->id.',
			"first_name": 			"'.$name.'",
			"last_name": 			"'.$lastname.'",
			"email": 				"'.$email.'",
			"recruiting_channel_id": '.$this->tt_api_channel_id.',
			"external_posting_id": 	"'.$post_id.'",
			"message": 				"'.$message.'",
			"application_date": 	"'.date('Y-m-d').'",
			"files": 				'.json_encode($userfiles).'
		}';

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $this->tt_api_application_post_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $body,
		CURLOPT_HTTPHEADER => array(
			'X-Company-ID: '.$this->tt_api_company_id,
			'Authorization: Bearer '.$this->tt_api_bearer,
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
		}
		curl_close($curl);

		$log['body'] 		= $body;
		$log['response'] 	= $response;
		$log['data']		= $submission->get_posted_data();
		$log['files']		= $submission->uploaded_files();
		$log['recipient']	= $recipient;
		
		
		update_post_meta($post_id, '_submissions_'.$date, $log);
				
	}

	function nv_add_job_details( $content )
	{
		global $post;

		$job = get_post_meta(get_the_ID(), 'job_data', true);
		
		if ( get_post_type( get_the_ID() ) == 'vacatures' ) {
		
			$category_detail	=	get_the_category(get_the_ID());//$post->ID
			
			if($job->department)
				$content .= "<div><h4>Department</h4> <p>".ucfirst(strtolower($job->department))."</p></div>";
			if($job->employmentType)
				$content .= "<div><h4>Employment Type</h4> <p>".ucfirst(strtolower($job->employmentType))."</p></div>";
			if($job->seniority)
				$content .= "<div><h4>Seniority</h4> <p>".ucfirst(strtolower($job->seniority))."</p></div>";
			if($job->yearsOfExperience)
				$content .= "<div><h4>Years Of Experience</h4> <p>".ucfirst(strtolower($job->yearsOfExperience))."</p></div>";
			if($job->keywords)
				$content .= "<div><h4>Keywords</h4> <p>".ucfirst(strtolower($job->keywords))."</p></div>";
			if($category_detail)
			{
				foreach($category_detail as $cd){
					$cname = $cd->cat_name;
				}
				$content .= "<div><h4>Location</h4> <p>".$cname."</p></div>";
			}
		}
			

		return $content;
	}

	public function import_jobs()
	{

		$url = $this->tt_api_job_url."?language=en";

		$args = array(
			'method'	=> 'GET',
			'headers'     => array(
				
			)
		);

		$response = wp_remote_post($url, $args);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
		} 
		else 
		{
			
			$body 			= wp_remote_retrieve_body( $response );
			
			$api_response 	= json_decode(json_encode(simplexml_load_string($body, null, LIBXML_NOCDATA))); 

	
			if(is_array($api_response->position))
			{
				foreach($api_response->position as $job)
				{
					
					$this->process_jobs_import($job);

					$alljobs[] = sanitize_title($job->name);
					
				}
			}
			else
			{
				$this->process_jobs_import($api_response->position);

				$alljobs[] = sanitize_title($job->name);
			}

		
			$args = array(  
				'post_type' => 'vacatures',
				'posts_per_page' => -1 
			);
		
			$loop = new WP_Query( $args ); 
				
			while ( $loop->have_posts() ) : $loop->the_post(); 
				
				$sync_date = get_post_meta(get_the_ID(), 'job_data_synced', true);
				$curr_date = time();
				$postTitle = sanitize_title(get_the_title());
				if(!in_array($postTitle, $alljobs))
				{
					echo "<br/>";
					echo get_the_title(). " <strong>deleted successfully</strong>";
					echo "<br/>";
					wp_delete_post(get_the_ID());
				}

			endwhile;

			wp_reset_postdata(); 
		
		}
	}

	public function process_jobs_import($job)
	{
		$str					= '';
		$jobTitle 				= sanitize_title($job->name);
		$recruitingCategory		= $job->office;
		if($job->schedule == "full-or-part-time")
			$jobschedule			= 'Full or Part-time';
		if($job->schedule == "full-time")
			$jobschedule			= 'Full-time';
		$jobpost 				= get_page_by_title($job->name, OBJECT, 'vacatures');
		
		$excerpt = '<div class="entryOffice"><strong>'.$job->office.'</strong></div>
			<div class="entrySchedule"><strong>'.$jobschedule.'</strong></div>';

		$args = array(
			'name'        => $job->name,
			'post_type'   => 'vacatures',
			'numberposts' => 1
		);
		
		$my_posts = get_posts($args);
		
		if($my_posts)
		{
			$post_id = $jobpost->ID;
			
			foreach($job->jobDescriptions->jobDescription as $jobDesc)
			{
				$str .= "<h4>$jobDesc->name</h4><p>$jobDesc->value</p></br>";
			}

			$my_post = array(
				'ID'            	=> $post_id,
				'post_content' 		=> $str,
				'post_excerpt' 		=> $excerpt,
			);
			wp_update_post( $my_post );

			$alljobs[] = $job->name;
			
			update_post_meta($post_id, 'job_data',$job);
			update_post_meta($post_id, 'job_data_synced', time());
									
			$this->set_job_location($recruitingCategory, $post_id);

			echo "<br/>";
			echo $job->name. " <strong>exists and updated successfully</strong>";
			echo "<br/>";
			
		}
		else
		{
			

			foreach($job->jobDescriptions->jobDescription as $jobDesc)
			{
				$str .= "<h4>$jobDesc->name</h4><p>$jobDesc->value</p></br>";
			}

			$post_id = wp_insert_post(
				array (
				'post_type' 		=> 'vacatures',
				'post_title' 		=> $job->name,
				'post_content' 		=> $str,
				'post_excerpt' 		=> $excerpt,
				'post_status' 		=> 'publish',
				'comment_status' 	=> 'closed',   // if you prefer
				'ping_status' 		=> 'closed',      // if you prefer
			));
			
			if ($post_id) {

				// insert post meta

				$alljobs[] = $job->name;
				
				update_post_meta($post_id, 'job_data',$job);

				$this->set_job_location($recruitingCategory, $post_id);
				update_post_meta($post_id, 'job_data_synced', time());
					
				echo "<br/>";
				echo $job->name. " <strong>imported successfully</strong>";
				echo "<br/>";
			}
		}
	}

	public function set_job_location($jobterm, $post_id)
	{
		
		$term = term_exists( $jobterm, 'career-category');
		if(!$term['term_id'])
		{
			$term = wp_insert_term($jobterm,'career-category',[]);
		}
		wp_set_post_terms( $post_id, $term['term_id'], 'career-category' );
		
	}

	public function nv_admin_menu() {
		add_menu_page(
			__( 'Personio Jobs Import', 'nv-manager' ),
			__( 'Personio Jobs Import', 'nv-manager' ),
			'manage_options',
			'nv-manager-page',
			array($this,'nv_admin_page_contents'),
			'dashicons-schedule',
			3
		);
	}

	public function nv_admin_page_contents() {
		?>
		<form method="POST" action="options.php">
		<?php
			settings_fields( 'nv-manager-page' );
			do_settings_sections( 'nv-manager-page' );
			submit_button();
		?>
		</form>
		<h1> <?php esc_html_e( 'Run Jobs Import', 'nv-manager' ); ?> </h1>
		<form method="POST">
			<input type='hidden' name='nvmanagerimport' value="yes"><br/>
			<?php
			submit_button('Import Jobs');
			?>
		</form>
		<br/>
		<br/>
		<?php

		if($_POST['nvmanagerimport'] == "yes")
		{
			$this->import_jobs();
		}
		
	} 

	public function nv_setting_section_callback_function() {
	
		echo '<p>Please add details below</p>';
	
	}

	public function nv_settings_init() {

		add_settings_section(
			'nv_manager_page_setting_section',
			__( 'API Settings', 'nv-manager' ),
			array($this,'nv_setting_section_callback_function'),
			'nv-manager-page'
		);

		add_settings_field(
			'tt_api_url',
			__( 'API URL', 'nv-manager' ),
			array($this,'tt_api_url'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
		
		register_setting( 'nv-manager-page', 'tt_api_url' );
	
		
		add_settings_field(
			'tt_api_key',
			__( 'API Client ID', 'nv-manager' ),
			array($this,'tt_api_key'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_key' );

		add_settings_field(
			'tt_api_version',
			__( 'API CLient Secret', 'nv-manager' ),
			array($this,'tt_api_version'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_version' );

		add_settings_field(
			'tt_api_job_url',
			__( 'API CLient Job Url', 'nv-manager' ),
			array($this,'tt_api_job_url'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_job_url' );

		add_settings_field(
			'tt_api_company_id',
			__( 'Company ID', 'nv-manager' ),
			array($this,'tt_api_company_id'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_company_id' );

		add_settings_field(
			'tt_api_bearer',
			__( 'Bearer Token', 'nv-manager' ),
			array($this,'tt_api_bearer'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_bearer' );

		add_settings_field(
			'tt_api_application_post_url',
			__( 'Job Application Submit Url', 'nv-manager' ),
			array($this,'tt_api_application_post_url'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_application_post_url' );


		add_settings_field(
			'tt_api_channel_id',
			__( 'Recruiting Channel ID', 'nv-manager' ),
			array($this,'tt_api_channel_id'),
			'nv-manager-page',
			'nv_manager_page_setting_section'
		);
	
		register_setting( 'nv-manager-page', 'tt_api_channel_id' );
		
	}

	public function tt_api_channel_id() {
		?>
		<input type="text" class="regular-text" id="tt_api_channel_id" name="tt_api_channel_id" value="<?php echo get_option( 'tt_api_channel_id' ); ?>">
	<?php
	}

	public function tt_api_application_post_url() {
		?>
		<input type="text" class="regular-text" id="tt_api_application_post_url" name="tt_api_application_post_url" value="<?php echo get_option( 'tt_api_application_post_url' ); ?>">
	<?php
	}

	public function tt_api_company_id() {
		?>
		<input type="text" class="regular-text" id="tt_api_company_id" name="tt_api_company_id" value="<?php echo get_option( 'tt_api_company_id' ); ?>">
	<?php
	}

	public function tt_api_bearer() {
		?>
		<input type="text" class="regular-text" id="tt_api_bearer" name="tt_api_bearer" value="<?php echo get_option( 'tt_api_bearer' ); ?>">
	<?php
	}

	public function tt_api_job_url() {
		?>
		<input type="text" class="regular-text" id="tt_api_job_url" name="tt_api_job_url" value="<?php echo get_option( 'tt_api_job_url' ); ?>">
	<?php
	}

	public function tt_api_url() {
		?>
		<input type="text" class="regular-text" id="tt_api_url" name="tt_api_url" value="<?php echo get_option( 'tt_api_url' ); ?>">
	<?php
	}

	public function tt_api_key() {
		?>
		<input type="text" class="regular-text" id="tt_api_key" name="tt_api_key" value="<?php echo get_option( 'tt_api_key' ); ?>">
	<?php
	}

	public function tt_api_version() {
		?>
		<input type="text" class="regular-text" id="tt_api_version" name="tt_api_version" value="<?php echo get_option( 'tt_api_version' ); ?>">
	<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Logic_Factory_Jobs_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Logic_Factory_Jobs_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/logic-factory-jobs-importer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Logic_Factory_Jobs_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Logic_Factory_Jobs_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/logic-factory-jobs-importer-admin.js', array( 'jquery' ), $this->version, false );

	}

}
