<?php

use Commander\LivCommander;

class LivCommanderTest extends \PHPUnit_Framework_TestCase
{

    public function testClassesExists()
    {
        $this->assertFileExists(__DIR__.'/../src/Commander/LivCommander.php');
        $this->assertFileExists(__DIR__.'/../src/Commander/MessageManager.php');
    }

    public function testMessagesExists()
    {

        $livia = new LivCommander();

        $this->assertFileExists($livia->getMessagePath().DIRECTORY_SEPARATOR.'welcome.livia');
        $this->assertFileExists($livia->getMessagePath().DIRECTORY_SEPARATOR.'end.livia');
        $this->assertFileExists($livia->getMessagePath().DIRECTORY_SEPARATOR.'starttask.livia');
        $this->assertFileExists($livia->getMessagePath().DIRECTORY_SEPARATOR.'endtask.livia');
        $this->assertFileExists($livia->getMessagePath().DIRECTORY_SEPARATOR.'help.livia');
    }

    public function testSetOption()
    {
        $livia = new LivCommander();

        $livia->setOption('help', 'Show Help List', array(
            array('name' => 'help', 'command' => 'echo "help"')
        ));

        $options = $livia->getOptions();

        $this->assertContains('help', $options);
    }
}
