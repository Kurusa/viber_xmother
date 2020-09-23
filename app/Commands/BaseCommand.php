<?php

namespace App\Commands;

use App\Models\User;
use App\ViberHelpers\ViberApi;
use App\ViberHelpers\ViberParser;

/**
 * Class BaseCommand
 * @package App\Commands
 */
abstract class BaseCommand
{

    protected $connection;

    /**
     * @var User
     */
    protected $user;

    protected $update;
    /**
     * @var ViberParser
     */
    protected $parser;

    /**
     * @var ViberApi
     */
    protected $viber;
    protected $text;

    public function __construct($update)
    {
        $this->update = $update;
        $this->parser = new ViberParser($update);
        $this->viber = new ViberApi();
        $this->viber->chat_id = $this->parser::getChatId();
        $this->user = User::where('viber_chat_id', $this->parser::getChatId())->first();
        $this->text = require(__DIR__ . '/../config/bot.php');
    }

    function handle()
    {
        $this->processCommand();
    }

    /**
     * @param $class
     */
    function triggerCommand($class)
    {
        (new $class($this->update))->handle();
    }

    abstract function processCommand();

}