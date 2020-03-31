# Morselo-server

> Morselo - the daily code excerpts for the developers. Collect & organize code, snippets and notes. Manage and share your snippets with ease.

## Project setup

For starting local development,

Cloning the project

```
git clone git@github.com:team-underground/morselo-server.git
```

cd to the project and run:

```
composer install
```

copy env file

```
cp .env.example .env
```

Setup database credential and run

```
php artisan migrate
```

Install passport keys

```
php artisan passport:install
```

get the client id and secret and put them in the .env file

```env
PASSPORT_CLIENT_ID=2
PASSPORT_CLIENT_SECRET=tsN2IOJ0kgA7yxPNxPvnZ5j1LAmYJUbVMpwqNJGp
```

create a github oauth app for `login using github`. Provide **Authorization callback URL** as `https://${SERVER_BASE_URL}/login/github/callback`.

get the client id and secret and put them in the .env file

```
GITHUB_CLIENT_ID=61e48aa8c7479b9ae45a
GITHUB_CLIENT_SECRET=c0e3e758efd4ddf9580e16170400695fb2e0fe32
```

## Roadmap for version 1.0.0

Morselo is still under heavy development, We decided to ship it in this early stage so you can help us make it better.

Here's the plan for what's coming:

-   [x] Social login via **Github**
-   [x] Feeds of latest snippet
-   [x] Lists all tags (categories) in categories page
-   [x] Tag wise feed listing
-   [x] Admin - create snippet
-   [x] Admin - edit snippet
-   [x] Dev can like, bookmark snippets if logged in
-   [x] List bookmarks and dev's snippets
-   [ ] Unit Test
-   [ ] Public search page using algolia
-   [ ] Social login via **Twitter**

## Roadmap for version 2.0.0

-   [ ] snippets can be make public and private
-   [ ] private snippets will not come in public search or feeds
-   [ ] follower and following concept
-   [ ] allow devs to store snippets as gists in github (exceptions to be handled when login using twitter)
-   [ ] many more to come...

## Credits

-   [Abhishek Sarmah](https://github.com/abhisheksarmah)
-   [Mithicher Baro](https://github.com/mithicher)

## Contributing

coming soon...

## License

Morselo is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```

```
