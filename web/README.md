## What's this all about, then?

These files present an example of how to use the Netaxept Payum Gateway, with a few 
pages/links that simulate a webshop and its backend in order to provide a (reasonably) easy
to understand implementation. 

### How to use?

Run `cp web/vars.php.dist web/vars.php` and change the credentials/settings accordingly.

Point your local web server's docroot to the `web` directory (or use the provided Docker
setup by running `make docker-start`) and visit [localhost](http://localhost).

Click one of the two links. Follow the steps. Easy. Analyse the code of all the files in
the `web` directory to see what's going on.