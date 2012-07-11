SparqlProxyPHP
==============

A PHP forward proxy for remote access to SPARQL endpoints; forwards request/response headers and filters out non-SPARQL URL arguments.

The proxy introduces an additional "serviceUrl" query string parameter which supports specifying a remote endpoint.


Example Usage:
  curl http://localhost/sparql-proxy.php?serviceUrl=http%3A%2F%2Flinkedgeodata.org%2Fsparql&query=Select+%2A+%7B+%3Fs+%3Fp+%3Fo+%7D+Limit+3


