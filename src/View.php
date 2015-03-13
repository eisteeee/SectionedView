<?php 
namespace SectionedView;
class View
{
    private $template_base;
    public $echo = true;
    public function __construct($template_base, $opts = array())
    {
        $this->template_base = $template_base;
        if(array_key_exists('echo', $opts) && !$opts['echo'])
        {
            $this->echo = false;
        }
    }

    public function render($template, $data)
    {
        if(!$this->echo)
        {
            ob_start();
        }
        $view = new Section($this->template_base, $template, $data);
        $view->render();
        if(!$this->echo)
        {
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }
}