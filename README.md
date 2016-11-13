Statusbase
==========

A system to track manuscripts for Features team purposes.

## Installation
### Development

1. Install dependencies with `composer install`
2. Create a database with `bin/console doctrine:database:create`
3. Update the schema with `bin/console doctrine:migrations:migrate`

### Production

1. Install production dependencies, update production database and clear caches with `./install-prod.sh`

## Notes

This is an event sourced system. The paper_events table is the source of truth and should never be deleted. Everything else is expendable.


