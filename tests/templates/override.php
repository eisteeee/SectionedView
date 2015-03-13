<?php 
use \SectionedView\Section;
Section::layout('layout2.php');
Section::start('content') ?><?= $key ?> override<?php Section::end() ?>