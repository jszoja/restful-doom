<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

// the name of the command is what users type after "php bin/console"
class Play extends Command
{
    protected static $defaultName = 'play';

    // the command description shown when running "php bin/console list"
    protected static $defaultDescription = 'Plays Restful Doom game';

    // ...
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('Controls Restful Doom via REST API from that command.')
            ->addArgument('steps', InputArgument::OPTIONAL, 'File with steps to exec during play')
            ->addOption('map', 'm', InputArgument::OPTIONAL, 'Doom map to load', 1)
            ->addOption('port','p',InputArgument::OPTIONAL, 'HTTP port for Doom REST API', 6666)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       $port = $input->getOption('port');

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://localhost:'.$port,
            // You can set any number of default request options.
            'timeout'  => 10.0,
        ]);

        $map = $input->getOption('map');

        // set map
        $client->patch('/api/world', ['json' => ['map' => 1]]);

        // executes API commands from the steps file
        $stepsFile = $input->getArgument('steps');
        if($stepsFile) {
            $fh = fopen($stepsFile, 'r');
            $counter = 0;
            $output->writeln("Executing steps from the file: $stepsFile");
            
            $actionMap = [
                's' => 'shoot', 
                'shoot' => 'shoot', 
                'f' => 'forward ',
                'forward' => 'forward',
                'b' => 'backward',
                'backward' => 'backward',
                'l' => 'turn-left',
                'left' => 'turn-left',
                'r' => 'turn-right',
                'right' => 'turn-right',
                'u' => 'use',
                'use' => 'use',
            ];

            while($cmd = fgets($fh)) {

                $cmd = rtrim($cmd);
                @list($action,$delay) = explode(' ', $cmd);
                $action = trim($action);

                if(!isset($actionMap[$action])) {
                    $output->writeln('ERR '.$cmd.' (skipped)');
                } else {
                    $action = $actionMap[$action];
                    $output->writeln("--> $cmd");
                    $client->post('/api/player/actions', ['json' => ['type' => $action]]);
                    usleep((int)$delay ?: 100);
                }

                
                

                $counter++;
            }

            

            $output->writeln('');
            $output->writeln('Done');
            $output->writeln("Executed $counter steps.");
        }


        $stdin = fopen('php://stdin', 'r');
        stream_set_blocking($stdin, 0);
        //system('stty cbreak -echo');

        while (1) {
        $keypress = fgets($stdin);
            if ($keypress) {
                $translatedKey = $this->translateKeypress($keypress);
                $output->writeln('Key pressed: ' . $translatedKey);

                if($translatedKey === 'ESC') {
                    break;
                }
            }
        }

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

    private function translateKeypress($string) {
        switch ($string) {
          case "\033[A":
            return "UP";
          case "\033[B":
            return "DOWN";
          case "\033[C":
            return "RIGHT";
          case "\033[D":
            return "LEFT";
          case "\n":
            return "ENTER";
          case " ":
            return "SPACE";
          case "\010":
          case "\177":
            return "BACKSPACE";
          case "\t":
            return "TAB";
          case "\e":
            return "ESC";
         }
        return $string;
      }
}