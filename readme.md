# Crafting as a Service

## Adding a new game

1. Decide on a slug.  No dashes.
2. Add that slug to all the `.env` files, comma separated
3. Add data to `storage/seeds/games.json`
4. Configure nginx to allow for `newSlug.craftingasaservice.com`
  - If applicable, also add it to your local hosts
5. `php artisan osmose:migrate`
  - This will create the schema/database for you, and migrate everything

## Making a migration specific to all games databases

php artisan make:migration --path database/migrations/games [Name]


