<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'facebook-php-sdk-master/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId'  => '*******************',
    'secret' => '*******************',
    'fileUpload' => true,
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.

        $user_profile = $facebook->api('/me');

    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
$fbUser = $facebook->api('/laurence.tureaud.73');



?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>php-sdk</title>
    <style>
        body {
            font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
        }
        h1 a {
            text-decoration: none;
            color: #3b5998;
        }
        h1 a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h1>php-sdk</h1>

<?php if ($user): ?>
    <a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php else: ?>
    <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
    </div>
<?php endif ?>

<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>

<?php if ($user): ?>
    <h3>You</h3>
    <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
    <?php

    try
    {
        //This example is for uploading a picture to event wall
        $mainImage = '@' . realpath('Images/mrT.jpg');
        $imgData = array(
            'picture' => $mainImage
        );

        $postResult = $facebook->api('/158836540967678/photos', 'post', $imgData );

        /*
        //Uncomment if you want to a post to the event feed
        $attachment =  array(
            'access_token' => $_SESSION['fb_273282822814393_access_token'],
            'message' => "php test",
            'name' => "testName",
            'description' => "Check Out new message",
            'link' => '',
        );

        $postResult = $facebook->api('/158836540967678/photos', 'post', $imgData );
        */


    }
    catch (FacebookApiException $e)
    {
        echo $e->getMessage();
    }
    ?>
    <h3>Your User Object (/me)</h3>
    <pre><?php print_r($user_profile); ?></pre>
<?php else: ?>
    <strong><em>You are not Connected.</em></strong>
<?php endif ?>

<h3>Public profile of Ron</h3>
<img src="https://www.facebook.com/laurence.tureaud.73/picture">
<?php echo $fbUser['name']; ?>
</body>
</html>