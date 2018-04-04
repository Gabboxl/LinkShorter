# LinkShorter

_A link. Shorter._

A simple PHP class which inlcude the most link-shortener services.
You can also log-in with your own credentials on all the services!

-----------------------------------------------------

The LinkShorter class is very simple to use,

here's an example:

`require "class-linkshorter.php";`

`$r = new LinkShorter($service, $link);`

replace `$service` variable with the name of the service (ex. adfly, adfocus, googl...) and the `$link` variable with the long url which you want to short.

To get the shorten link, use the `getLink` function: `$r->getLink();`

**You can also get errors with the** `getError` **method.** (Please visit the relative section)

There is a full working example in `example.php`.

You can find also a `method-specific documentation` (for adfly method, handling errors etc..) [here](/methods).


This class and the documentation **are still in developement**, so you can find bugs or other issues.
