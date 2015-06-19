<?php
/**
 * Created by PhpStorm.
 * User: gcwioro
 * Date: 18.06.15
 * Time: 13:59
 */

use bitcodin\Bitcodin;
use bitcodin\UrlInput;
use bitcodin\FtpInput;
use bitcodin\Input;

require_once __DIR__.'/vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('3bf186b02a8cc7caf40b1dbad22ad5421f0390f685ca4dca88f31d28135b4709');

/* CREATE URL INPUT */
$input = UrlInput::create(array('url' => 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv'));

/* CREATE FTP INPUT */
$ftpInput = FtpInput::create(array('url' => 'ftp://ftp3788:ixuref@data.uni-klu.ac.at/Homepage_Summer_v10.webm', 'username'=> 'yourUser', 'password'=> 'password'));

/* ANALYZE INPUT */
$input->analyze();

/* GET INPUT */
$input = Input::get($input->inputId);

/* GET LIST OF INPUTS */
$inputResponse = Input::getList();
$inputsList = $inputResponse->inputs;       //List of inputs
$inputsPerPage = $inputResponse->perPage;   //Inputs per page
$inputsTotal = $inputResponse->totalCount;  //Total count of inputs

/* ANALYZE ALL INPUTS */
$inputResponse = Input::getList();
for($page = 1; $page * $inputResponse->perPage <= $inputResponse->totalCount; $page++)
{
    foreach(Input::getList($page)->inputs as $input)
        $input->analyze();
}

/* DELETE INPUTS */
$input->delete();
$ftpInput->delete();


/* DELETE ALL INPUTS */
/*$inputResponse = Input::getList();
for($page = 1; $page * $inputResponse->perPage <= $inputResponse->totalCount; $page++)
{
    foreach(Input::getList($page)->inputs as $input)
        Input::delete($input);
}*/