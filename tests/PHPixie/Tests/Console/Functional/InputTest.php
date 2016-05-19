<?php

namespace PHPixie\Tests\Console;

class InputTest extends \PHPixie\Test\Testcase
{
    protected $tempDir;
    protected $file;

    public function setUp()
    {
        $this->tempDir = sys_get_temp_dir();
        $this->file = $this->tempDir.'/phixie_console_test.php';
    }

    public function testGetOptions()
    {
        $this->assertSame(
            array(
                'test' => '5'
            ),
            $this->runFile('--test=5', 'getOptions', array(
                array('test')
            ))
        );

        $this->assertSame(
            array(
                'config' => '5',
                'force' => true,
                'debug' => true,
                'test' => '5',
                'v' => '8'
            ),
            $this->runFile('-c5 -f -t --debug --test=5 -v8', 'getOptions', array(
                array('test', 'debug', 'force', 'config'),
                array('d', 'v'),
                array('debug', 't', 'force'),
                array(
                    'f' => 'force',
                    'c' => 'config'
                )
            ))
        );
    }

    public function testGetCurrentDirectory()
    {
        $this->assertSame($this->tempDir, $this->runFile('', 'getCurrentDirectory'));
    }

    public function testGetArguments()
    {
        $this->assertSame(
            array('pixie', 'trixie'),
            $this->runFile('pixie -c=5 trixie', 'getArguments')
        );
    }


    public function tearDown()
    {
        if(file_exists($this->file)) {
            //unlink($this->file);
        }
    }

    protected function runFile($params, $method, $args = array())
    {
        $path = realpath(__DIR__.'/../../../../../vendor/autoload.php');
        $args = var_export($args, true);

        $file = "
        <?php

        require '$path';
        \$input = new \\PHPixie\\Console\\Input();
        \$result = call_user_func_array(array(\$input, '$method'), $args);
        echo serialize(\$result);
        ";

        file_put_contents($this->file, $file);
        $result = trim(shell_exec("cd {$this->tempDir} && php {$this->file} $params"));
        return unserialize($result);
    }
}
