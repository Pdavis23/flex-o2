Granola
===================
- https://gitlab.com/wholegrain/granola
- https://gitlab.com/wholegrain/granola/wikis/home
- https://gitlab.com/wholegrain/granola/issues

# Requirements
- PHP >7.4 (composer dependencies)
- Node v16.13.0 (npm >8) (there is an .nvmrc file in the root of project)

# Project Setup
- update _src/static/site.webmanifest with project specifics
- update _src/images/icon-512.png
- update _src/images/icon-maskable-512.png
- update build/wordpress.js

# Getting Started
- create .env.js with a development url
- npm run setup (runs `npm install && composer install && gulp`)
- npm start (runs gulp watch)
- npm run deploy (runs a production build)
