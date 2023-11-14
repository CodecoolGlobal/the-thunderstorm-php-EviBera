<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class OutputCommand extends Command
{
    protected const MSG_SPACE   = "\x20";
    protected const MSG_PLAIN   = 0;
    protected const MSG_SUCCESS = 1;
    protected const MSG_ERROR   = 2;
    protected const MSG_PREFIXES = array(
        self::MSG_SUCCESS => '<fg=black;bg=green>',
        self::MSG_ERROR   => '<fg=white;bg=red>',
    );

    public function __construct()
    {
        parent::__construct();
    }

    protected function output(string $message, int $messageType = self::MSG_PLAIN)
    {
        if (self::MSG_PLAIN === $messageType) {
            $this->getOutput()->writeln($message);
        }
        else {
            $this->getOutput()->writeln(self::MSG_PREFIXES[$messageType] . $this->formatOutputMessage($message) . '</>');
        }
    }

    protected function formatOutputMessage(string $message)
    {
        $message   = (self::MSG_SPACE . $message . self::MSG_SPACE);
        $extraLine = str_repeat(self::MSG_SPACE, strlen($message));

        return ($extraLine . PHP_EOL . $message . PHP_EOL . $extraLine);
    }
}
