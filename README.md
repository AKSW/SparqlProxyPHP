#SparqlProxyPHP

A PHP forward proxy for remote access to SPARQL endpoints; forwards request/response headers and filters out non-SPARQL URL arguments.

## Introduction
The proxy introduces an additional "serviceUrl" query string parameter which supports specifying a remote endpoint. The main motivation is to access remote SPARQL endpoints from within java script.
Note that this proxy consists of a single file that only does the forwarding of requests and response (headers).
If you are looking for a proxy that also features a UI, you may want to check out `http://logd.tw.rpi.edu/ws/sparqlproxy.php`.

## Deployment (Ubuntu):
You need apache, php and php-curl installed:
    sudo apt-get install libapache2-mod-php5 php5-curl

Make the script accessible via apache, e.g.
 * Clone the project
 *     cp sparql-proxy.php /var/www

## Example Usage:
    curl http://localhost/sparql-proxy.php?serviceUrl=http%3A%2F%2Flinkedgeodata.org%2Fsparql&query=Select+%2A+%7B+%3Fs+%3Fp+%3Fo+%7D+Limit+3


