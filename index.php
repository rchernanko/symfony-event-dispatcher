<?php

use App\Events\UserSignedUp;
use App\Listeners\SendThankYouEmail;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

//Always need to do this somewhere for all my classes to automatically load!
require 'vendor/autoload.php';

//Generally, you'll have one instance of the EventDispatcher class in your project (also known as a singleton).
//That way, you can add listeners to that class anywhere in your project + then dispatch events to all of those listeners

//Ok so let's do this. First let's create an event dispatcher "container" but with no listeners bound to it yet...
$dispatcher = new EventDispatcher;

//Now let's add a listener to the event dispatcher instance...

//Think of the listener as 'someone listening for somebody else to broadcast something that took place'

//We are using a closure in the below. The closure will accept an event as an argument
$dispatcher->addListener(UserSignedUp::class, function(Event $event) {
    var_dump($event);
});

$dispatcher->addListener(UserSignedUp::class, function(Event $event) {
    var_dump('Send notification to the user\'s friends');
});

//So at this point, we've registered 2 listeners (above), but there haven't been any events fired yet (for them to listen in on).
//If we were to try and run this file in the browser (to see the results of the closure), nothing would happen...

//So let's dispatch an event...imagine this is kept in the e.g. UserController
$dispatcher->dispatch(UserSignedUp::class);

//Awesome, so now when you check out this file (up to this point) in the browser, you'll see the var_dumps above...
//i.e. the event has fired and the listeners have heard it, and done something as a result. All good.


//Ok, next. At present, we are not sending through other information with the event...i.e. which user signed up, what
//was their email etc. In these situations it's useful to create an dedicated event class...see the app/events directory:

$event = new UserSignedUp( (object) ['name' => 'joe', 'email' => 'joe@example.com']); //Would normally pass in a new User object here but i've not created this so far
$dispatcher->dispatch(UserSignedUp::class, $event);

// At this point, I can now access any of the information associated with the event e.g.
// $event->user


// So you create an event that corresponds to the thing that took place


//Ok in the above, we are using closures for the listeners. And this is fine for really basic things. But for more
//complex cases, you might want to create a specific Listener Class - let's create a SendThankYouEmail listener class within
//app>listeners. Ok that's done...

//Now, instead of adding a listener with a closure, we can do something like this:

$listener = new SendThankYouEmail();

$dispatcher->addListener(UserSignedUp::class, [$listener, 'handle']);

//FYI = Need to dispatch the event again so that the above is listening to it...
$dispatcher->dispatch(UserSignedUp::class);