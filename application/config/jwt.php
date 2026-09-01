<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------
| JWT Secure Key
|--------------------------------------------------------------------------
*/
$config['jwt_key'] = '04f22a6a2096ee51e2a583d4b0f4dc9c';
/*
|-----------------------
| JWT Algorithm Type
|--------------------------------------------------------------------------
*/
$config['jwt_algorithm'] = 'HS256';
/*
|-----------------------
| Token Request Header Name
|--------------------------------------------------------------------------
*/
$config['token_header'] = 'authorization';

/*
|-----------------------
| Token Type
|--------------------------------------------------------------------------
*/
// $config['token_type'] = 'vanila'; // use header authorization
$config['token_type'] = 'bearer'; // use header authorization & Bearer

/*
|-----------------------
| Token Expire Time

| https://www.tools4noobs.com/online_tools/hh_mm_ss_to_seconds/
|--------------------------------------------------------------------------
| ( 1 Day ) : 60 * 60 * 24 = 86400
| ( 1 Hour ) : 60 * 60     = 3600
| ( 1 Minute ) : 60        = 60
*/
$config['token_expire_time'] = 86400;