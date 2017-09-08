<?php

use Symfony\Component\EventDispatcher\EventDispatcher;

//Always need to do this somewhere for all my classes to automatically load!
require 'vendor/autoload.php';

//Creates an event dispatcher "container" but with no listeners bound to it yet...
$dispatcher = new EventDispatcher;

//Now let's add a listener...

//Think of the listener as 'someone listening for somebody else to broadcast something that took place'
$dispatcher->addListener('UserSignedUp');