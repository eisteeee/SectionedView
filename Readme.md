SectionedView
=============

SectionedView implements layout inheritance and sections in php's native templates. It aims to be simple an easy to integrate

##Features
* Integrates seamlessly with php's native templating (à la `<?php ?>`)
* easy to integrate with frameworks such as [Slim PHP](http://www.slimframework.com/)

Getting Started
--------------
###Install

I recommend you install SectionedView via Composer:

composer.json:

    "require": {
      "eisteee/sectioned-view": "~1.0"
    }

or using the command line

    composer require eisteee/sectioned-view


###Requirements

Only *PHP >= 5.3.0* is required

###Quick Tutorial

Instantiate a SectionedView renderer:

    $template_path = __DIR__ . "/templates"; //absolute path to your template directory
    $view = new \SectionedView\View($template_path);
    $view->render("hello.php", array('name' => 'World'));


templates/hello.php:

    Hello, <?= $name ?>!

renders

    Hello, World!

in order to return the rendered contents instead of printing them to output directly use:

    $template_path = __DIR__ . "/templates"; //absolute path to your template directory
    $view = new \SectionedView\View($template_path, array('echo' => 'false'));
    $view->render("hello.php", array('name' => 'World'));

###Using layouts & sections

considering the same setup as above, now we will use a layout file

templates\layout.php:
    
    <?php use SectionedView\Section; ?>
    <html>
    <head>
       <title>Hello</title>
    </head>
    <body>
       <?php Section::output('content') ?>
    </body>
    </html>


templates\hello.php:
    
    <?php use SectionedView\Section; ?>
    <?php Section::layout('layout.php') /* path relative to specified template_path in SectionedView */ ?>
    <!-- the moment you specify a layout file any text that is not within a section gets ommited -->
    <!-- you can only specify one layout per file -->
    <?php Section::start('content') ?>
       Hello, <?= $name ?>!
    <?php Section::end() ?>

will render:

    <html>
    <head>
       <title>Hello</title>
    </head>
    <body>
       Hello, World!
    </body>
    </html>

###Slim PHP Views

a view class for use in [Slim PHP](http://www.slimframework.com/) projects can be achieved as follows:

    class SectionedSlimView extends \Slim\View
    {
        private $sectionedView;
        public function __construct($template_base)
        {
            parent::__construct();
            $this->sectionedView = new \SectionedView\View($template_base);
        }

        public function render($template)
        {
            $this->sectionedView->render($template, $this->data->all());
        }
    }

register it as your Slim view:
    
    $template_path = __DIR__ . "/templates"; /* path relative to specified template_path in SectionedView */
    $app = new \Slim\Slim(array(
        "view" => new SectionedSlimView($template_path);
    ));

and you are good to go