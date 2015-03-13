<?php 
namespace SectionedView;
class SectionOptions
{
   private $options;
   private $filter = null;
   public function __construct($options = [])
   {
      $this->options = $options;
      if(array_key_exists('filter', $options))
      {
         $this->filter = $options['filter'];
      }
   }

   public function apply($contents, $data)
   {
      if($this->filter !== null)
      {
         $contents = $this->applyFilter($contents, $data);
      }
      return $contents;
   }

   private function applyFilter($contents, $data)
   {
      if(is_callable($this->filter))
      {
         $contents = call_user_func_array(
            $this->filter,
            [$contents, $data]);
      }
      else if(is_array($this->filter) || $this->filter instanceof Traversable) 
      {
         $contents = $contents;
         foreach($this->filter as $filter) {
            if(!is_callable($filter))
            {
               throw new ViewException("one or more specified section filters is not callable");
            }
            $contents = call_user_func_array(
               $filter,
               [$contents, $data]);
         }
         $contents = $contents;
      }
      else
      {
         throw new ViewException("specified section filter is neither callable nor an array or a Traversable");
      }
      return $contents;
   }
}