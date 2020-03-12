# Crafting Ninja

- https://crafting.ninja
- https://reddit.com/r/CraftingNinja
- https://www.patreon.com/craftingasaservice

## System Requirements

```
sudo apt-get install php-bcmath (for Hashids)
```

## Data Notes

### Migrating a specific game

```
mysql -u homestead -psecret -e "DROP SCHEMA caas_ffxiv"
mysql -u homestead -psecret -e "CREATE SCHEMA caas_ffxiv"
php artisan migrate:full
php artisan aspir ffxiv
php artisan osmose ffxiv
php artisan assets ffxiv
```
