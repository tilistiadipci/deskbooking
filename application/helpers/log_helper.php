<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists('storelog')) {
    function storelog($activity, $userid)
    {
        // get main CodeIgniter object
        $ci = get_instance();
       	$ci->load->model('Model_Log');
        // Write your logic as per requirement
        
    }

    function getAgent(){
    	$ci = get_instance();
    	$ci->load->library('user_agent');

		if ($ci->agent->is_browser())
		{
		        $agent = "use Browser ". $ci->agent->browser().' '.$ci->agent->version();
		}
		elseif ($thicis->agent->is_robot())
		{
		        $agent = "use Robot ".$ci->agent->robot();
		}
		elseif ($this->agent->is_mobile())
		{
		        $agent = "use Mobile ".$ci->agent->mobile();
		}
		else
		{
		     $agent = 'Unidentified User Agent';
		}
		return $agent;
    }
}

?>