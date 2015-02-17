<?php

require_once 'bootstrap.php';
require 'Entities\MailList.php';
require 'Entities\Project.php';
require 'Entities\Schedule.php';
require 'Entities\Email.php';
require 'Entities\Sub.php';
require 'Entities\SubList.php';
require 'Entities\SendList.php';

require 'AutoSender.php';

const FILES_PATH = 'files/';
const API_KEY = ''; // set empty to skip Mandrill API testing
//const API_KEY = 'xIGhgER646bvggGv8nwX4g'; // test
//const API_KEY = 'DwZxbtgrGRrhH6rDob09Tw'; // prod

$IS_DEBUG = false;

clog();

if ($argc > 1)
{
    if ($argv[1] === 'test')
    {
        $IS_DEBUG = true;

        dlog("Arg 1: " . $argv[1]);
        dlog();
    }
    else
    {
        echo "Usage: php $argv[0] [test]\n";
        echo "       test - for testing mode\n";
        echo "\n";
        exit(0);
    }
}

clog("--* App started ----------------------------------------------------");

$emailSender = new EmailSenderMandrill;
$emailSender->setKey(API_KEY);
$autoSender = new AutoSender($entityManager, $emailSender);
$autoSender->process();

clog("--* App finished ---------------------------------------------------");
clog();

exit(0);

/**
 * clog
 *
 * console log
 */
function clog($str = '')
{
    if (!empty($str))
    {
        echo date('Y-m-d H:i:s '), $str;
    }
    echo "\n";
}

/**
 * dlog
 *
 * console debug log
 */
function dlog($str = '')
{
    global $IS_DEBUG;
    if ($IS_DEBUG)
    {
        clog($str);
    }
}