# Genius - Cryptocurrency exchange platform
A web application developed as part of a team IT project.  
Kazimierz Wielki University, Bydgoszcz, Poland

## Description
Polkryptex is a revolutionary cryptocurrency management tool. It allows you to exchange not only cryptocurrencies but also traditional ones. Store all your savings with us! IT'S SAFE!

## Depencies
The application uses several solutions to improve work and design. They are necessary to work on it.
- Composer - [getcomposer.org](https://getcomposer.org/download/)
- Node JS - [nodejs.org](https://nodejs.org/en/download/)
- PHP 8.0.11 - [php.net](https://windows.php.net/download#php-8.0)

To install all PHP modules, enter comand in the application directory
```powershell
composer install
```

To download all node modules, enter the command
```powershell
npm install
```

To build all the resources and the startup directory
```powershell
npm run build
```

To run all tests
```powershell
./vendor/bin/pest.bat
```

## Modules
- [x] URL Routing
- [x] Accepting Ajax requests
- [x] Automated installer
- [x] Toast notifications
- [x] User accounts
- [ ] User roles
- [ ] Login and registration
- [ ] Internal payment system combined with the account balance of user accounts
- [ ] Top-up module with service provider (PayPal /= PayU)
- [ ] Cryptocurrency exchange system
- [ ] Exchange of cryptocurrencies with their current rates and historical rates presented in the charts
- [ ] (Optional) Forecasting further rate of a given cryptocurrency

## Technologies & Tools
- **PHP** - *Scripting language responsible for the application logic.*
- **MySQL** - *Relational database system.*
- **Sass** - *Preprocessor for CSS.*
- **TypeScript** - *Strongly typed superset of JavaScript.*
- **webpack** - *JavaScript module bundler.*
- **WorkBox** - *Library for managing and creating Service Workers.*
- **Composer** - *PHP dependency management tool.*
- **NPM** - *JavaScript dependency management tool.*
- **PWA** - *Set of tools and technologies for building scalable web applications.*

## Timeline
- 01.06.2021 - Creation of CMS and base functionalities
- 01.07.2021 - User accounts and registration
- 01.08.2021 - User's wallet mechanism
- 01.09.2021 - Currency exchange capability
- 01.10.2021 - Connection to the cryptocurrency API
- 01.11.2021 - Implementing the PayPal API
- 01.12.2021 - Optional, PWA support

## External solutions
- Node.js [nodejs.org](https://nodejs.org/en/)
- TypeScript [typescriptlang.org](https://www.typescriptlang.org/)
- Bramus Router [github.com/bramus](https://github.com/bramus/router)
- The Laravel Components [github.com/illuminate](https://github.com/illuminate)
- PHP Mailer [github.com/PHPMailer](https://github.com/PHPMailer/PHPMailer)
- Web Auth [github.com/web-auth](https://github.com/web-auth/webauthn-lib)
- Symfony var-dumper [symfony.com](https://symfony.com/doc/current/components/var_dumper.html)
- Pest [pestphp.com](https://pestphp.com/docs/writing-tests)
- Spatie Ray [spatie.be](https://spatie.be/products/ray)
- Bootstrap [getbootstrap.com](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
- Webpack [webpack.js.org](https://webpack.js.org/)
- Webpack CLI [npmjs.com/package/webpack-cli](https://www.npmjs.com/package/webpack-cli)
- SASS [npmjs.com/package/sass](https://www.npmjs.com/package/sass)
- Workbox Webpack [npmjs.com/package/workbox-webpack-plugin](https://www.npmjs.com/package/workbox-webpack-plugin)

## Roles
- @Leszek - Core functions and Content Management System
- @Szymon - Databases & AJAX
- @Kacper - UI elements and APIs
- @Filip - Designing graphical interfaces, images and vectors
- @Paweł - Frontend
