<?php 
namespace SectionedView;
class Section
{
   private static $current_instance = null;
   public static function __callStatic($name, $arguments)
   {
      return call_user_func_array(Section::$current_instance->$name, $arguments);
   }

   private $template_base;
   private $template;
   public $data;

   private $layout = null;
   private $available_sections = [];
   private $section_stack = [];

   public function __construct($template_base, $template, $data, $sections = [])
   {
      $this->template_base = $template_base;
      $this->template = $template;
      $this->data = $data;
      $this->available_sections = $sections;
      $this->section_options = new SectionOptions;
   }

   public function render()
   {
      Section::$current_instance = $this;
      extract($this->data);
      ob_start();
      require $this->template_base . DIRECTORY_SEPARATOR .  $this->template;
      if (count($this->section_stack) > 0)
      {
         throw new ViewException("missing section end");
      }
      if ($this->layout !== null) {
         ob_end_clean();
         $view = new Section(
            $this->template_base,
            $this->layout,
            $this->data,
            $this->available_sections
         );
         $view->render();
      } 
      else
      {
         ob_end_flush();
      }
   }


   public function output($name, $options = [])
   {
      $this->start($name, $options);
      $this->end(true);
   }

   public function layout($layout)
   {
      if($this->layout !== null) 
      {
         throw new ViewException("only one layout per section allowed");
      }
      $this->layout = $layout;
   }

   public function start($name, $options = [])
   {  
      foreach($this->section_stack as $section)
      {
         if($section['name'] === $name)
         {
            throw new ViewException("cannot nest sections with identical names. Section: $name");
         }
      }
      $options = new SectionOptions($options);
      array_push($this->section_stack, ['name' => $name, 'options' => $options]);
      ob_start();
   }

   public function end($echo = false)
   {
      if(count($this->section_stack) === 0) 
      {
         throw new ViewException("cannot end Section without starting one");
      }
      $contents = ob_get_contents();
      ob_end_clean();

      $section = array_pop($this->section_stack);
      $contents = $section['options']->apply($contents, $this->data);

      if(!array_key_exists($section['name'], $this->available_sections))
      {
         if($echo)
         {
            echo $contents;
         }
         else
         {
            $this->available_sections[$section['name']] = $contents;
         }
      }
      else
      {
         echo $this->available_sections[$section['name']];
      }
   }

   public function show()
   {
      $this->end(true);
   }
}
