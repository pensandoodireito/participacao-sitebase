<?php

// Prevent loading this file directly
if ( !defined('SENDPRESS_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}


/**
* SendPress_Cron
*
* @uses
*
* @package  SendPress
* @author   Josh Lyford
* @license  See SENPRESS
* @since 	0.8.8.5
*/
class SendPress_Cron {
	private static $instance;


    /**
     * Alternative function to the current wp_cron function that would usually executed on sanitize_comment_cookies
     */
    static public function auto() {



    }

	static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$class_name = __CLASS__;
			self::$instance = new $class_name;
		}
		return self::$instance;
	}

	function __construct(){
        //$this->auto();
		  /* some processing for cron management */
        add_action( 'wp_loaded', array( $this , 'auto_cron' ) );
        add_filter( 'cron_schedules', array( $this , 'cron_schedules' ) );

	}

    function auto_cron(){
          // make sure we're in wp-cron.php
        if ( false !== strpos( $_SERVER['REQUEST_URI'], '/wp-cron.php' ) ) {
            // make sure a secret string is provided in the ur
            if ( isset( $_GET['action'] ) && $_GET['action'] == 'sendpress' ) {
                $time_start = microtime(true);
                $count = SendPress_Data::emails_in_queue();
                $bg = 0;
                if($count > 0){
                    SendPress_Queue::send_mail();
                    $count = SendPress_Data::emails_in_queue();
                } else {
                    SPNL()->log->prune_logs();
                    SendPress_Data::clean_queue_table();
                    //SendPress_Logging::prune_logs();
                    $bg = 1;
                }

               

                
                $attempted_count = SendPress_Option::get('autocron-per-call',25);
                $pro = 0;

                if(defined('SENDPRESS_PRO_VERSION')){
                    $pro = SENDPRESS_PRO_VERSION;
                }
                $stuck = SendPress_Data::emails_stuck_in_queue();
                $limit = SendPress_Manager::limit_reached();
                $emails_per_day = SendPress_Option::get('emails-per-day');
                $emails_per_hour =  SendPress_Option::get('emails-per-hour');
                $hourly_emails = SendPress_Data::emails_sent_in_queue("hour");
                $emails_so_far = SendPress_Data::emails_sent_in_queue("day");
                $limits = array('autocron'=> $attempted_count,'dl'=>$emails_per_day,'hl'=>$emails_per_hour,'ds'=>$emails_so_far,'hs'=>$hourly_emails);
                $time_end = microtime(true);
                $time = $time_end - $time_start;
                echo json_encode(array("background"=> $bg , "queue"=>$count,"stuck"=>$stuck,"version"=>SENDPRESS_VERSION,"pro"=> $pro ,"limit" => $limit, 'info'=>$limits ,'time'=> number_format( $time , 3 ) ) );
                die();
            }

        }
    }

    function cron_schedules( $param ) {
        $frequencies=array(
            'one_min' => array(
                'interval' => 60,
                'display' => __( 'Once every minutes', 'sendpress')
                ),
            'two_min' => array(
                'interval' => 120,
                'display' => __( 'Once every two minutes','sendpress')
                ),
            'five_min' => array(
                'interval' => 300,
                'display' => __( 'Once every five minutes','sendpress')
                ),
            'ten_min' => array(
                'interval' => 600,
                'display' => __( 'Once every ten minutes','sendpress')
                ),
            'fifteen_min' => array(
                'interval' => 900,
                'display' => __( 'Once every fifteen minutes','sendpress')
                ),
            'thirty_min' => array(
                'interval' => 1800,
                'display' => __( 'Once every thirty minutes','sendpress')
                ),
            'two_hours' => array(
                'interval' => 7200,
                'display' => __( 'Once every two hours','sendpress')
                ),
            'eachweek' => array(
                'interval' => 2419200,
                'display' => __( 'Once a week','sendpress')
                ),
            'each28days' => array(
                'interval' => 604800,
                'display' => __( 'Once every 28 days','sendpress')
                ),
            'monthly' => array(
                'interval' => 2419200,
                'display' => __( 'Once Monthly' )
                )
            );

        return array_merge($param, $frequencies);
    }
        static function stop(){
        $upload_dir = wp_upload_dir();
        $filename = $upload_dir['basedir'].'/sendpress.pause';
        if (file_exists($filename)) {
            return true;
        }
        return false;
    }

    static function start(){
        $upload_dir = wp_upload_dir();
        $filename = $upload_dir['basedir'].'/sendpress.pause';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    static function iron_url($url){
        return  parse_url($url);

    }


    static function use_iron_cron(){

        $url = SendPress_Cron::remove_http( site_url() );
        $domain = base64_encode( $url );
        //SendPress_Error::log( 'http://api.sendpress.com/set/'. $domain .'/'. SENDPRESS_CRON);
        $body = wp_remote_retrieve_body( wp_remote_get( 'http://api.sendpress.com/set/'. $domain .'/'. SENDPRESS_CRON ) );
        wp_clear_scheduled_hook( 'sendpress_cron_action' );
    }

    static function get_info(){

        $url = SendPress_Cron::remove_http( site_url() );
        $domain = base64_encode( $url );
        $transient_key = 'sendpress_autocron_cache';
            $data          = get_transient( $transient_key );

            // bail if transient is set and valid
            if ( $data !== false ) {
                return $data;
            }


        //SendPress_Error::log( 'http://api.sendpress.com/set/'. $domain .'/'. SENDPRESS_CRON);
        $body = wp_remote_retrieve_body( wp_remote_get( 'http://api.sendpress.com/get/'. $domain  ) );
        // Make sure to only send tracking data once a week
        set_transient( $transient_key, $body, 60 * 5 );
        return $body;

    }

    static function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}

    function disable_iron_cron(){

    }
}
