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

> If you find any error like `Key path "file:///app/storage/oauth-public.key" does not exist or is not readable`. Please try this below options:

-   1. Generating the passport keys forcefully

```
php artisan passport:keys --force
```

-   2. Try clearing all the config and app caches

```
php artisan config:clear
php artisan cache:clear
```

-   3. If these above steps does not work, then generate `public outh key` and `private oauth key` manually.

```
openssl genrsa -out storage/oauth-private.key 4096

openssl rsa -in storage/oauth-private.key -pubout > storage/oauth-public.key
```

-   4. If the problem is not resolved yet, then copy the contents of `public outh key` and `private oauth key` to env:

```
PASSPORT_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
MIIJKwIBAAKCAgEA3lxCjoyZBg4prZE5qy64KPvIHwgEx+vFdh3il4RFzmmH/+y3
zOhJyPR4+1VK+uk0fEcIKT1sa9WYMkkx2g54QOxoomz/tn3YNDS0c4DJwiCcWxAx
Sp8T9h+s99kS9fIQirOViIcGdpfQT4IuSqSKztepRWq9IJfVgQH7TcrbDq+Z2q3e
vnSc3Pggrw8w9ZweI7Gkp5Ci8U4hNiCKR1TVNtAHYmBMC8SEvmiaqtPMyKEaoIXm
ybn7cSgJueROiFVYIKLrmjvORNN0WbUi08rmm4FeQFAlaGQ5YeDR+oV0diFioY8i
LAqd11OYsuNfu6GamyvGli8k4CfayaVKsBClp3Tefw+9Aza1lfHqPyyDPhxd3xIp
FqZXGds/Mb71bZC9kMMssMWwJ3vXWBh1KjynYOayJE6x2fjuKyyu15x/0mkprKTP
xTa1K8fZU2quzuj0juoM32bPnFqUf5VjstNrqtTTSvVx0s9evGn6JRwV4H6nbsZd
e4FGc5FnCYeuJ3/ixwzrMv+YXQa7DL4c5wnGGgSr9yXBQrn/YBO1FcLDAtQFDg1b
MULm5JcuD8rSMDVNdQD+Cc2KmqZoqGyhLSq5PCFzdxc/VQ3iUJ5qvOlYx5LetbP6
tPhySupLVy8tqp9ujyz3IeKB67motK3Tt91J/zm3Pm0VVnMVk9QRJkrT/ysCAwEA
AQKCAgEArFv3e2egdgEkbVXUzcw5FAuW4/bjPdSUCyq/KZNuSMGkmcxz7HFiQFLA
NJG4vSVnea3VtaHXGP9IKjJyYgQ1Up3tvXmf/WUu4ci4149M1R1SFYRHsP/+CRok
iTnKaLpp+BmcH2gKqoKjBf5yw4knz417uEM0tr760DUpBvPnOUsEh22e6yKZNO9g
5lFwMfIBqslYSErlAwb812gBx6kKXKKEFs1uJPD/tFiBC0mBw0Kd0S/55QQ001Gl
uGIwPZ1p4mJnHfadvNvrpF2z0VfFyWROGqwFUxXdJPGP0hEAZVEbkv/QDTNthitW
gdOdsWNi5ETaei+w9c/u1784VaGRxPnm6c8bYBTqEnYyULwMZW2/B6WbSbPopTR7
eV8rMCPM1nvaPp4UorrGCkJwxYqhGTlATtUKqZZB3SCxTuGCTfb5tjMvleG5TbPF
FtWAAsCXo4IPAsLEacf45Lv5Tp4iQSc/GOgC1honzxJjip5cKjCr66RdnXifVt5f
Bm6/e3vWVujNSklNdsjzlWcpibwDSQcuSvuLroVBNuw/w8giVtMQDN2pVYHk3cFs
uvyvfz+niU7h0YwHNfNqL+DSFd1jqsImyVpBThlyMzudpnH+KLbyYk9cPMnjCKZH
LDDFJdNVlAwabIWEmwQ7hD29FteeyFMCixDSqamZuEQymvN0EYECggEBAPQ2E3t6
ubInox0VdeZRLP3QAkuVjKcTkRCby08BsOECGKn5Qbug+V7tsR3fZpbXmxtkv488
tUmvVhjgf+FAF2oZkJ4chGqOcLswKAIE8ZzQ6TZ/WUAUM18SsPk/lGqMIbebgz3G
3KKHVgCNtIIJJOwd59l/Y8fUjMqeTu62SCPTTqxOZGc5o5MQeVqhn0N5+9V587qG
HPyw8UHljNWf73XJ9vCKKXoTXwZRnWo5V2p6hHZe04obulZ/jyjilk8omYXJevwO
1EY0HECh2KnIprJOGBNGrBfoT7eX4IPDKwGUXef/qRZyX+MPgSIb2sE/E64sVLd/
HPkDjTGkQPTqiR0CggEBAOkYJ5PpCLCbnlRxvN2NacLa/4D6WH6RDe0QV4w678+Y
qnLG7wmjxujcueJXJ7uMWLr3kATpqRMB8I8tfPfgbbTDmx1T+8WLMsy1DKKuvcSg
7O3qi/Kb5rPk6veV1ZA3WF2j0s79xy7eIF+ned1Xbv1r9FawH9YqTf6M4JXf37Sp
3Ni63c0QHBxKtw2yWJko23NfExeVkD8z2CKRcx3OgAeuJkhTT/jo+JfStWn6WVIW
OG5Yaq8HqPJMawxsJt2IXrA3g1E9CgngSt+DihsYVsr7FMo8KEq6v+NcsX1J+K4V
A93Eoa1BIYYEJ5DfMCJ29uel2l9npfFiuiCOcTKufucCggEBAJhREh22EhRYPJhr
Rjn8737vOj9Ca5PZ1GTzhv8kItp0oEWDvcf1QxQ+VHY5XJdAqvGPCRuh1cC7p4uk
mp43h2MZxuWXC9AaP53BD3MS+k/AzXS8QUKCVvg5hEAxs7Qw6ubGDDl/yyVkG/QE
bk2cvna2wGEb33RH0fiefMTO1Hfj1/IIgv8PKEMVx1sL63X+o2AmzCnOdE33XsKn
Fgw30Pc6nNayleQaKO6cAP67/RXgjLX3tyEw4iwJK5Voni+JNxgG7ro/1Y5j+isl
xyk+iRo1MzbyoWvX7cCo7dPA6xX1IA0PnLrvY3cPuI85qFtXqEK+S6iXk86MtIf9
1rXrgtECggEBAOSwCBR7q0TqSNp8hFmxpjPAfLDj7PKwCcOuJtaz9BfupjvZpSgG
AN7xv4WrEJZWya8Vt7y6KmE1o/g1Hshq7fdNed+6R+e1++8PKghp/FDvucRqO0eZ
YSzZyRWDcXX+drnYQrlGDqeS0pxGSa+5WnFT+vMZ9QI/mynEuYsRi/9KMByKqZXI
2mt/ejz73yTVCwkUHD0lRdJcYQ75OMHjmYTvfSu0YV/J4ivCVK5XKk748gCAyppS
n5SpGNWlPjRW2N92d2W6xyCNZ6gG+/Y9WNnooKsRfk5jDmRYyuIrMspIX/SyOBUt
M2CyrsbZ0fXeVbtL4wV7QTmkLQxH9BNPU70CggEBALx0W8OCYpTvRHa9sFMH0V2q
oPtU6hyoWzm2XeXB+UnKWco5bA7wbbEpUTlprAptQPUEBDbySWt1M0xn7IQlrl62
Fr1Ui9ZQkCcEYSKc6eVSRHGYTSVVrMZeOUh2LxfaTll78jmrMwldaqGRg3NA1yW0
deLifTRFl3X6JfNlMCE3AfcKG7A9peBk5QkXnf8Q3Bvz1+W0U1IQQYFt1affhbw1
bc0F5F4SbNNl6uy3gkvwgovRKjWXIxYpwhZoMjciD6gCpCcgqKvO/9GA8CNX8JkS
jpiam0+ahxuPjAHp8QKgoBov2mVXi3jgKD18s1Fo/t09QV3A1Cg2FEGoygmU92g=
-----END RSA PRIVATE KEY-----"
```

```
PASSPORT_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA3lxCjoyZBg4prZE5qy64
KPvIHwgEx+vFdh3il4RFzmmH/+y3zOhJyPR4+1VK+uk0fEcIKT1sa9WYMkkx2g54
QOxoomz/tn3YNDS0c4DJwiCcWxAxSp8T9h+s99kS9fIQirOViIcGdpfQT4IuSqSK
ztepRWq9IJfVgQH7TcrbDq+Z2q3evnSc3Pggrw8w9ZweI7Gkp5Ci8U4hNiCKR1TV
NtAHYmBMC8SEvmiaqtPMyKEaoIXmybn7cSgJueROiFVYIKLrmjvORNN0WbUi08rm
m4FeQFAlaGQ5YeDR+oV0diFioY8iLAqd11OYsuNfu6GamyvGli8k4CfayaVKsBCl
p3Tefw+9Aza1lfHqPyyDPhxd3xIpFqZXGds/Mb71bZC9kMMssMWwJ3vXWBh1Kjyn
YOayJE6x2fjuKyyu15x/0mkprKTPxTa1K8fZU2quzuj0juoM32bPnFqUf5VjstNr
qtTTSvVx0s9evGn6JRwV4H6nbsZde4FGc5FnCYeuJ3/ixwzrMv+YXQa7DL4c5wnG
GgSr9yXBQrn/YBO1FcLDAtQFDg1bMULm5JcuD8rSMDVNdQD+Cc2KmqZoqGyhLSq5
PCFzdxc/VQ3iUJ5qvOlYx5LetbP6tPhySupLVy8tqp9ujyz3IeKB67motK3Tt91J
/zm3Pm0VVnMVk9QRJkrT/ysCAwEAAQ==
-----END PUBLIC KEY-----"
```

Now, clearing the cache will work fine.

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
