<?php 
class SectionedViewTest extends PHPUnit_Framework_TestCase
{
    protected $view;

    protected function setUp()
    {
        $template_path = __DIR__ . '/templates';
        $this->view = new \SectionedView\View($template_path);
    }

    public function testRendersSimpleViewWithData()
    {
        $this->expectOutputString('test');
        $this->view->render('simple.php', array('key' => 'test'));
    }

    public function testRendersSimpleViewWithSection()
    {
        $this->expectOutputString('layout test');
        $this->view->render('section.php', array('key' => 'test'));
    }

    public function testRenderViewWithOverridenSections()
    {
        $this->expectOutputString('layout test override');
        $this->view->render('override.php', array('key' => 'test'));   
    }

    /**
     * @expectedException SectionedView\ViewException
     * @expectedExceptionMessage missing section end
     */
    public function testThrowsWhenSectionNotClosed()
    {
        $this->view->echo = false;
        $this->view->render('missing_close.php', array('key' => 'test'));   
    }

}