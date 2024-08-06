# SocialNetworksPublisher

Allow to Post on social networks.

# Install

```console
git clone git@github.com:logipro-fr/SocialNetworksPublisher.git:
```

# To Contribute to SocialNetworkPublisher

### Requirements

- Docker
- Git
- Twitter API access

To use SocialNetworksPublisher, you need a Twitter Client ID, which can be obtained when creating an application on the Twitter Developer Portal. In this application, you also need to set up the User authentication settings field. In the App permissions field, select Read and Write.

Next, you need a refresh token, which will be used later to set up the microservice. This token can be obtained in several steps. First, create an authorization URL. The authorization URL must include the different scopes that the application requires, such as tweet.read, users.read, tweet.write, and follows.write. Each scope added to the URL should be separated by %20. For example, if your application needs to read and write tweets, the URL should include tweet.read%20tweet.write%20users.read.

The URL must also contain several other essential pieces of information. The code_challenge should be replaced with a random string in production for enhanced security. The client_id, which you can find in the Developer Portal under the "Projects & Apps" tab, is also required. This portal also provides a redirect_uri, which is the callback URL that redirects the user after they have authorized or denied the request. Finally, to obtain a refresh token, you need to include the offline_access scope.

Example authorization URL:

https://twitter.com/i/oauth2/authorize?response_type=code&client_id=YOUR_CLIENT_ID&redirect_uri=https://www.example.com&scope=tweet.write%20offline.access&state=state&code_challenge=challenge&code_challenge_method=plain

Example response:

https://www.example.com?state=state&code=VGNibzFWSWREZm01bjN1N3dicWlNUG1oa2xRRVNNdmVHelJGY2hPWGxNd2dxOjE2MjIxNjA4MjU4MjU6MToxOmFjOjE

Exchanging the authorization code for an access token and a refresh token:

The application sends a request to Twitter's authorization server with the authorization code, client ID, client secret, and the redirect URL (see the request below).

```console
curl --location --request POST 'https://api.twitter.com/2/oauth2/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'code=VGNibzFWSWREZm01bjN1N3dicWlNUG1oa2xRRVNNdmVHelJGY2hPWGxNd2dxOjE2MjIxNjA4MjU4MjU6MToxOmFjOjE' \
--data-urlencode 'grant_type=authorization_code' \
--data-urlencode 'client_id=client_id' \
--data-urlencode 'redirect_uri=https://www.example.com' \
--data-urlencode 'code_verifier=challenge'

```

Response:
```json
{
    "token_type": "bearer",
    "expires_in": 7200,
    "access_token": "TGlWVjRRS0xLSndNMzdZaTEtc3dsT2QtMDctS1AyX1pxeXZZYlVqWDZmZXFFOjE3MjI5MjkxNjE3MjI6MToxOmF0OjE",
    "scope": "tweet.write users.read tweet.read offline.access",
    "refresh_token": "UFJLNVpEckRBYmZCcUM5d0pmQTgxbV9Uc0pCRFdJV0RxVlE3c1FwS1ExdzF6OjE3MjI5MjkxNjE3MjI6MTowOnJ0OjE"
}
```

Before testing, you need to set up a `secret.env` file at the root of the project containing two fields:

- `TWITTER_REFRESH_TOKEN=YOUR_REFRESH_TOKEN`
- `TWITTER_CLIENT_ID=YOUR_TWITTER_CLIENT_ID`

These fields will allow the refresh token loop imposed by Twitter. You can find your new tokens in the var directory. Be careful: if you delete the files in your var directory, you must save your last refresh token and put it in the `secret.env` file before the next restart to avoid losing it.

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