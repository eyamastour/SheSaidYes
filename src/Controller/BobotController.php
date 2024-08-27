<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;


class BobotController extends Controller{

    /**
     * @Route("/messagebot", name="message")
     */
    function messageAction(Request $request)
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Configuration for the BotMan WebDriver
        $config = [];

        // Create BotMan instance
        $botman = BotManFactory::create($config);

        // Give the bot some things to listen for.
        $botman->hears('(hello|hi|hey)', function (BotMan $bot) {
            $bot->reply('Hello!');
        });
        $botman->hears('(help|issue)', function (BotMan $bot) {
            $bot->reply('Contact Us On our email');
        });
        $botman->hears('(thank you|tnx|nice)', function (BotMan $bot) {
            $bot->reply('youre welcome');
        });
    
        $botman->hears('(bye|see you|byebye)', function (BotMan $bot) {
            $bot->reply('bye and Have Nice Day');
        });
        

        // Set a fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand.');
        });

        // Start listening
        $botman->listen();

        return new Response();
    }
    
    /**
     * @Route("/messaagebot", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('bobot/homepage.html.twig');
    }
    
    /**
     * @Route("/chatframebot", name="chatframe")
     */
    public function chatframeAction(Request $request)
    {
        return $this->render('bobot/chat_frame.html.twig');
    }
}