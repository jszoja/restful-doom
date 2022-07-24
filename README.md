# About

This is PHP command line communicating to [Restful Doom game](https://github.com/jeff-1amstudios/restful-doom) via HTTP REST API on default 6666 port.

```shell
$  php .\game.php play --help 
Description:
  Plays Restful Doom game

Usage:
  play [options] [--] [<steps>]

Arguments:
  steps                 File with steps to exec during play

Options:
  -m, --map=MAP         Doom map to load [default: 1]
  -p, --port=PORT       HTTP port for Doom REST API [default: 6666]
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug      

Help:
  Controls Restful Doom via REST API from that command.
  ```

  # Usage with steps loaded from txt file

  You can record the steps for game in txt file and have it executed automatically:

  ```shell
  php .\game.php play steps.txt
  ```

  Each line has single command and delay im ms:

  ```
  <action> <delay>
  ```

  Allowed actions:

  ```
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

    # not supported
    #'u' => 'use',
    #'use' => 'use',
```