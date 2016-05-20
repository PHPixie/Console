<?php

namespace PHPixie\Tests\Console;

class SapiTest extends \PHPixie\Test\Testcase
{
    protected $tempDir;
    protected $file;

    public function setUp()
    {
        $this->tempDir = sys_get_temp_dir();
        $this->file = $this->tempDir.'/phixie_console_test.php';
    }

    public function testOptions()
    {
        for($i = 0; $i<2; $i++) {
            $this->assertSame($this->tempDir, $this->runFile('', 'currentDirectory'));
        }
    }

    public function testCurrentDirectory()
    {
        for($i = 0; $i<2; $i++) {
            $this->assertSame($this->tempDir, $this->runFile('', 'currentDirectory'));
        }
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
        \$context = (new \\PHPixie\\Console(new \\PHPixie\\Slice()))->context();
        \$result = call_user_func_array(array(\$context, '$method'), $args);
        echo serialize(\$result);
        ";

        file_put_contents($this->file, $file);
        $result = trim(shell_exec("cd {$this->tempDir} && php {$this->file} $params"));
        return unserialize($result);
    }
}
