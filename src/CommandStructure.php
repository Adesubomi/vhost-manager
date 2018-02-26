<?php namespace therealsmat;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class CommandStructure extends command {

    protected $sites_available_dir = '/etc/apache2/sites-available/';

    protected $sites_enabled_dir = '/etc/apache2/sites-enabled/';

    protected function getAvailableSites()
    {
        $command = "cd {$this->sites_enabled_dir} && ls";
        $process = new Process($command);
        $process->run();

        return $process->getOutput();
    }

    protected function ask($question, $input, $output, $default = NULL)
    {
        $helper = $this->getHelper('question');

        $question = new Question($question, $default);

        return $helper->ask($input, $output, $question);
    }

    protected function runCommand($command, $showRealTimeOutput = false)
    {
        $process = new Process($command);

        if($showRealTimeOutput) {
            $process->run(function($type, $buffer){
                echo $buffer;
            });
            return;
        }

        $process->run();

        return $process->getOutput();
    }

    protected function ensureDirectoryExists($domain_dir)
    {
        if (! file_exists($domain_dir)) {
            mkdir($domain_dir);
        }
    }
}
