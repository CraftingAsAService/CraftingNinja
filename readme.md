# Crafting Ninja

- https://crafting.ninja
- https://reddit.com/r/CraftingNinja
- https://www.patreon.com/craftingasaservice

## Data Notes

```
mysql -u homestead -psecret -e "DROP SCHEMA caas_ffxiv"
mysql -u homestead -psecret -e "CREATE SCHEMA caas_ffxiv"
php artisan osmose:migrate
php artisan db:seed --class FfxivGameSeeder --database ffxiv
```

## System Requirements

```
sudo apt-get install php-bcmath (for Hashids)
```
