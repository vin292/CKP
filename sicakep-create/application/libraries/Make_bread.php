<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Make_bread
{
    private $_breadcrumb = array();
    private $_include_home;
    private $_container_open;
    private $_container_close;
    private $_divider;
    private $_crumb_open;
    private $_crumb_close;

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->helper('url');
        $CI->load->config('make_bread', TRUE);
        //$this->_include_home = $CI->config->item('include_home', 'make_bread');
        $this->_container_open = $CI->config->item('container_open', 'make_bread');
        $this->_container_close = $CI->config->item('container_close', 'make_bread');
        $this->_divider = $CI->config->item('divider', 'make_bread');
        $this->_crumb_open = $CI->config->item('crumb_open', 'make_bread');
        $this->_crumb_close = $CI->config->item('crumb_close', 'make_bread');
        /*if(isset($this->_include_home) && (sizeof($this->_include_home) > 0))
        {
            $this->_breadcrumb[] = array('title'=>$this->_include_home, 'href'=>rtrim(base_url(),'/'));
        }*/
    }

    public function add($title = NULL, $href = '', $segment = FALSE)
    {

        if (is_null($title)) return;
        if(isset($href) && strlen($href)>0)
        {
            if ($segment)
            {
                $previous = $this->_breadcrumb[sizeof($this->_breadcrumb) - 1]['href'];
                $href = $previous . '/' . $href;
            }
            elseif (!filter_var($href, FILTER_VALIDATE_URL))
            {
                $href = site_url($href);
            }
        }

        $this->_breadcrumb[] = array('title' => $title, 'href' => $href);
    }

    public function output()
    {

        $output = $this->_container_open;
        if(sizeof($this->_breadcrumb) > 0)
        {
            foreach($this->_breadcrumb as $key=>$crumb)
            {

                $output .= $this->_crumb_open;
                if(strlen($crumb['href'])>0)
                {
                    $output .= anchor($crumb['href'],$crumb['title']);
                }
                else
                {
                    $output .= $crumb['title'];
                }
                $output .= $this->_crumb_close;

                if($key < (sizeof($this->_breadcrumb)-1))
                {
                    $output .= $this->_divider;
                }
            }
        }

        $output .= $this->_container_close;
        return $output;
    }
}
