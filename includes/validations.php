<?php

// *************************************************************************
//  Express Invoice & Stock Manager
//  Copyright (c) Rajesh Dukiya. All Rights Reserved
// *************************************************************************
//
//  Email: support@skyresoft.com
//  Website: https://www.skyresoft.com
//
// *************************************************************************
// * This software is furnished under a license and may be used and copied
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.
// * If you Purchased from Codecanyon, Please read the full License from
// * here- http://skyresoft.com/licenses/standard/
// *************************************************************************


$user->addValidation(array(
    'first_name' => array('limit' => '0-15', 'regEx' => '/\w+/'),
    'last_name' => array('limit' => '0-15', 'regEx' => '/\w+/'),
    'website' => array('limit' => '0-50', 'regEx' => '@((https?://)?([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@')));
