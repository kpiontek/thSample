<?php
require('models/system.model.php');

class system extends system_model
{
	private $model;
  public $db;
  public $data;

  function __construct()
  {
    $this->model = new system_model;
    $this->db = $this->model->database;
    $this->data['categories'] = $this->get_menu();
  }

  // put together the categories menu
  private function get_menu() 
  {
    $categories = $this->model->get_categories();
    foreach($categories as $id => $cat) {
      if ($cat['parent_id'] > 0) {
        $parent = $this->model->get_category($cat['parent_id']);
        $cat['url'] = '/themes/category/'.$id.'/'.$this->seoencode($parent['title']).'/'.$this->seoencode($cat['title']);
        $categories[$cat['parent_id']]['children'][$id] = $cat;
        unset($categories[$id]);
      }
      else
        $categories[$id]['url'] = '/themes/category/'.$id.'/'.$this->seoencode($cat['title']);
    }
    return $categories;
  }

  // load a view and pass the data to it
	public function loadView($view) 
  {
    $data = $this->data;
		include('views/header.php');
		include('views/'.$view.'.php');
		include('views/footer.php');
	}

  // Encode variable to be url/seo friendly
  public function seoencode($text)
  {
    $text = preg_replace("%[^-/+|\w ]%", '', $text);
    $text = strtolower(trim($text, '-'));
    $text = preg_replace("/[\/_|+ -]+/", '-', $text);
    $text = trim($text, '-');
    return $text;
  }

  // Decode variable that was url/seo friendly
  public function seodecode($text)
  {
    $text = str_replace('-', ' ', $text);
    $text = rawurldecode($text);
    return $text;
  }

  // Print an array/object in a user-friendly manner
  public function debug($array)
  {
    $raw_data = print_r($array, TRUE);
    $raw_data = str_replace('Array', '<span class="array_title">Array</span>', $raw_data);
    echo '<div class="debug">';
    $backtrace = debug_backtrace();
    $title = $backtrace[0]['file'].' - line '.$backtrace[0]['line'];
    $this->debug_loop($array, $title);
    //echo $raw_data;
    echo '</div>';
  }

  // Helper function for debug()
  public function debug_loop($array, $title = NULL)
  {
    if (is_array($array))
      $type = 'Array';
    elseif(is_object($array))
      $type = 'Object';
    else
      $type = 'String';
    
    if ($type != 'String') {
      $num_items = count($array);
        
      echo '<div class="array">
        <div class="title compact">'.$type.' ['.$title.']
        <span class="num_items">'.$num_items.' items</span>
        </div>
        <div class="array_content">';
        
        $root_level = array();
        foreach($array as $key => $value)
        {
          if (is_array($value))
            $this->debug_loop($value, $key);
          else
            $root_level[] = '<div class="line">['.$key.'] => '.$value.'</div>';
        }
        foreach($root_level as $line)
          echo $line;
          
      echo '</div></div>';
    }
  }

  // safely compress css files
  private function compress_css()
  {
    
    $current_file = 'cache_'.sha1(date('Ymd'));
    $path_to_current_file = SERVER_ROOT.'/css/'.$current_file.'.css';
    
    if (!file_exists($path_to_current_file)) {
      $file_content = '';
      foreach($GLOBALS['data']['css'] as $key => $css) {
        $file_content .= file_get_contents(SERVER_ROOT.$css['path_to_file']);
        if ($file_content)
          unset($GLOBALS['data']['css'][$key]);
      }

      if (count($GLOBALS['data']['css']) < 1) unset($GLOBALS['data']['css']);
      
      $file_content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $file_content);
      $file_content = str_replace(array('\r', '\n'), '', $file_content);
      $file_content = str_replace('{ ', '{', $file_content);
      $file_content = str_replace(' }', '}', $file_content);
      $file_content = str_replace('; ', ';', $file_content);
      $file_content = str_replace(', ', ',', $file_content);
      $file_content = str_replace(' {', '{', $file_content);
      $file_content = str_replace('} ', '}', $file_content);
      $file_content = str_replace(': ', ':', $file_content);
      $file_content = str_replace(' ,', ',', $file_content);
      $file_content = str_replace(' ;', ';', $file_content);
      $file_content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $file_content);
      
      $old_cache_find = SERVER_ROOT.'/css/cache_*.css';
      $old_cache_files = glob($old_cache_find);
      
      foreach($old_cache_files as $file)
        unlink($file);
      
      file_put_contents($path_to_current_file, $file_content);
    }
    
    
    
    unset($GLOBALS['data']['css']);
    add_css('/css/'.$current_file.'.css');
  }

  // safely compress js files
  private function compress_js()
  {
    $current_file = sha1(date('Ymd'));
    $path_to_current_file = SERVER_ROOT.'/js/header_'.$current_file.'.js';
    
    if (!file_exists($path_to_current_file))
    {
      $file_content = '';
      foreach($GLOBALS['data']['js_header'] as $key => $js)
      {
        $file_content .= file_get_contents(SERVER_ROOT.$js['path_to_file']);
        if ($file_content)
          unset($GLOBALS['data']['js_header'][$key]);
      }
      if (count($GLOBALS['data']['js_header']) < 1) unset($GLOBALS['data']['js_header']);
      
      $file_content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $file_content);
      $file_content = str_replace(array('\r', '\n'), '', $file_content);
      $file_content = str_replace('{ ', '{', $file_content);
      $file_content = str_replace(' }', '}', $file_content);
      $file_content = str_replace('; ', ';', $file_content);
      $file_content = str_replace(', ', ',', $file_content);
      $file_content = str_replace(' {', '{', $file_content);
      $file_content = str_replace('} ', '}', $file_content);
      $file_content = str_replace(': ', ':', $file_content);
      $file_content = str_replace(' ,', ',', $file_content);
      $file_content = str_replace(' ;', ';', $file_content);
      $file_content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $file_content);
      
      $old_js_find = SERVER_ROOT.'/js/header_*.js';
      $old_js_files = glob($old_js_find);
      
      foreach($old_js_files as $file)
        unlink($file);
      
      file_put_contents($path_to_current_file, $file_content);
    }
    
    $path_to_current_file = SERVER_ROOT.'/js/footer_'.$current_file.'.js';
    
    if (!file_exists($path_to_current_file))
    {
      $file_content = '';
      foreach($GLOBALS['data']['js_footer'] as $key => $js)
      {
        $file_content .= file_get_contents(SERVER_ROOT.$js['path_to_file']);
        if ($file_content)
          unset($GLOBALS['data']['js_footer'][$key]);
      }
      if (count($GLOBALS['data']['js_footer']) < 1) unset($GLOBALS['data']['js_footer']);
      
      $file_content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $file_content);
      $file_content = str_replace('{ ', '{', $file_content);
      $file_content = str_replace(' }', '}', $file_content);
      $file_content = str_replace('; ', ';', $file_content);
      $file_content = str_replace(', ', ',', $file_content);
      $file_content = str_replace(' {', '{', $file_content);
      $file_content = str_replace('} ', '}', $file_content);
      $file_content = str_replace(': ', ':', $file_content);
      $file_content = str_replace(' ,', ',', $file_content);
      $file_content = str_replace(' ;', ';', $file_content);
      $file_content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $file_content);
      
      $old_js_find = SERVER_ROOT.'/js/footer_*.js';
      $old_js_files = glob($old_js_find);
      
      foreach($old_js_files as $file)
        unlink($file);
      
      file_put_contents($path_to_current_file, $file_content);
    }
    
    unset($GLOBALS['data']['js_header']);
    unset($GLOBALS['data']['js_footer']);
    add_js_footer('/js/footer_'.$current_file.'.js');
    add_js_header('/js/header_'.$current_file.'.js');
  }
}
