# SocialNetworksPublisher

Allow to Post on social networks.

# Install

```console
git clone git@github.com:
```

# To Contribute to SocialNetworkPublisher

## Requirements

* docker
* git


## Unit test

```console
bin/phpunit
```

Using Test-Driven Development (TDD) principles (thanks to Kent Beck and others), following good practices (thanks to Uncle Bob and others).

## Manual tests

```console
./start
```
Then, you can access Swagger at http://172.17.0.1:10902/ in your browser to test different routes. 
You can also access phpMyAdmin at http://172.17.0.1:10990/.

In `docker/mariad/db.env`, you can set a new password for the root user.

To stop the application, use:

```console
./stop
```

## Quality

Some indicators that seem interesting.

* phpcs PSR12
* phpstan level 9
* 100% coverage obtained naturally thanks to the “classic school” TDD approach
* we hunt mutants with “Infection”. We aim for an MSI score of 100% for “panache”


Quick check with:
```console
./codecheck
./bin/phpunit
```

Check coverage with:
```console
bin/phpunit --coverage-html var
```
and view 'var/index.html' with your browser

Check infection with:
```console
bin/infection
```
and view 'var/infection.html' with your browser