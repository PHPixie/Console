# Console
PHPixie console command component

[![Author](http://img.shields.io/badge/author-@dracony-blue.svg?style=flat-square)](https://twitter.com/dracony)
[![Source Code](http://img.shields.io/badge/source-phpixie/console-blue.svg?style=flat-square)](https://github.com/phpixie/console)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://github.com/phpixie/di/blob/master/LICENSE)

PHPixie Console allows you to define and run commands in the command line. The main benefit as opposed to just keeping
a folder with PHP scripts are the ability to define options and arguments, validate them, generate command usage etc.

## Migrating an Existing PHPixie Project

If you create a fresh PHPixie project you are already ready to go, otherwise you need to do a few small 
modifications to the project, by updating some files from the new project skeleton:

1. https://github.com/PHPixie/Project/blob/master/console
2. https://github.com/PHPixie/Project/blob/master/src/Project/Framework/Bundles.php
3. Add `"phpixie/framework-bundle": "~3.0"` to `composer.json`

Optionally also copy the Console factory and the example Greet Command:

4. https://github.com/PHPixie/Project/blob/master/bundles/app/src/Project/App/Console.php
5. https://github.com/PHPixie/Project/blob/master/bundles/app/src/Project/App/Console/Greet.php
6. Register the Console class in your Builder, like here: https://github.com/PHPixie/Project/blob/master/bundles/app/src/Project/App/Builder.php

## Running commands

Now try running the console script:
```
cd your_project_directory/
./console
```

This will give you the list of available commands and their descriptions, e.g.:

```
Available commands:

app:greet                     Greet the user
framework:installWebAssets    Symlink or copy bundle web files to the projects web folder
framework:generateBundle      Generate a new bundle
help                          Print command list and usage
```

You can get extended information about a command by using the `help` command:

```
./console help framework:installWebAssets

framework:installWebAssets [ --copy ]
Symlink or copy bundle web files to the projects web folder

Options:
copy    Whether to copy web directories instead of symlinking them
```

## Default commands

**framework:installWebAssets**
Its purpose is creating symlinks from bundle directories to the /web/bundles folder, e.g. /web/bundles/app ->
/bundles/app/web. The idea behind it is that we can have bundles that are installable and updatable via composer 
that provide their own web assets. The `--copy` flag will copy the directories instead of symlinking them.
This is usefull if you want to deploy the files to some CDN network afterwards.

**framework:generateBundle**
This command generates and registers a new bundle within your project.

## Adding your own commands

There is a sample `app:greet` command provided in the skeleton project. They are added in the same was as HTTP Processors,
using the `\Project\App\Console` class. To add a new command you have to add it's name to the array returned by the 
`commandNames` method, and create a `build<command_name>Command` method.

You can configure your command to add a description and define options and arguments. Let's look at the default `Greet` command:

```php
namespace Project\App\Console;

class Greet extends \PHPixie\Console\Command\Implementation
{
    public function __construct($config)
    {
        // Specify command description
        $config->description('Greet the user');
        
        //Define a 'message' argument
        $config->argument('message')
            ->description("Message to display");
        
        parent::__construct($config);
    }
    
    /**
     * Gets called when the command is executed.
     * $argumentData and $optionData work in the same
     * way as HTTP $request->query() and $request->data()
     */
    public function run($argumentData, $optionData)
    {
        $message = $argumentData->get('message', "Have fun coding!");
        $this->writeLine($message);
    }
}
```

### Arguments and options
Let's say we want to define a command that dumps some tables from the database, a typical call might look like this:

```
sqldump --user=root --skip-missing -f myDatabase users items
```

Here `myDatabase` is the name of the database, followed by the names of the tables we want to dump. These are the
arguments of our command. The `user`, `skip-missing` and `f` are options. Note that for arguments the order in which
they are specified matters, but for the options it does not, also short one letter options can be referenced with a single
`-` instead of two.

Let's look at defining options:

```php
$config->option('user')

    //Mark option as required
    ->required()
    
    //Describe what the option does.
    //this is displayed by the 'help' command
    ->description("User to connect to the database with");
    
$config->option('skip-missing')
    ->description("Don't throw an error if the tables are missing")
    
    //mark option as flag,
    //flag options don't accept a value,
    //but are set to 'true' if they are present.
    ->flag();

$config->option('f')
    ->flag()
    ->description("Force database dump");
```

When defining arguments you have to keep in mind that they should be defined in the same order in which they should be
specified. In out case this means we have to define a `database` argument before the `tables` one:

```php
$config->argument('database')
    ->required()
    ->description("Which database to dump the tables from");
    
$config->argument('tables')
    ->description("Tables to dump")
    
    // Can accept more than one value.
    // There can be only one argument marked with `arrayOf`
    // and it has to be the last one.
    ->arrayOf();
```

If we were to run the `help` command now, we would see the following:

```
./console help app:sqldump

app:sqldump --user=VALUE [ -f ] [ --skip-missing ] DATABASE [ TABLES... ]

Options:
user            User to connect to the database with
f               Force database dump
skip-missing    Don't throw an error if the tables are missing

Arguments:
DATABASE  Which database to dump the tables from
TABLES    Tables to dump
```

When the command is executed the `run` method of the command receives the passed options and arguments, which
can be accessed in the same way as when working with HTTP requests:

```php
public function run($argumentData, $optionData)
{
    $database = $argumentData->get('database');
    
    // specifying default value
    $user = $optionData->get('user', 'phpixie');
}
```

### Input and Output
The easiest way to return output from the command is by `return`ing a string. But some commands take a while
to process and you may want to provide users with intermediate status. There are some additional methods you can use:

```php
public function run($argumentData, $optionData)
{
    // Write text without line break
    $this->write("Hello ");
    
    // Write text with new line
    $this->writeLine("World");
    
    // Read a line of user input
    $str = $this->readLine();
    
    // Throwing a CommandException will output the error message
    // and ensure that the command exits with a non-zero exit code
    throw new \PHPixie\Console\Exception\CommandException("Something bad happened");
}
```

To further control input and out you can use the CLI context, in fact the above methods are just shortcuts
to CLI context calls:

```php
public function run($argumentData, $optionData)
{
    $context = $this->cliContext();
    
    $inputStream = $cliContext->inputStream();
    $outputStream = $cliContext->outputStream();
    $errorStream = $cliContext->errorStream();
    
    $outputStream->write("Hello");
    $errorStream->writeLine("Something bad happened");
    $context->setExitCode(1); // set the exit code
}
```

The exit code matters for if you want to check externally if the command was successful or not, e.g. in Bash
if you do something like:

```
if ./console app:somecommand ; then
    echo "Command succeeded"
else
    echo "Command failed"
fi
```




