# Inventory

__WARNING: Under Develop__

Inventory is a fully tested, PSR compliant Laravel inventory solution. It provides the basics of inventory management using Eloquent such as:

- Inventory Item management
- Inventory Item Variant management
- Inventory Stock management (per location)
- Inventory Stock movement tracking
- Inventory SKU generation
- Inventory Assembly management (Bill of Materials)
- Inventory Supplier management
- Inventory Transaction management

All movements, stocks and inventory items are automatically given the current logged in user's ID. All inventory actions
such as puts/removes/creations are covered by Laravel's built in database transactions. If any exception occurs
during an inventory change, it will be rolled back automatically.

Depending on your needs, you may use the built in traits for customizing and creating your own models, or
you can simply use the built in models.

### Requirements

- Laravel 4.* | 5.*
- Laravel's Auth, Sentry or Sentinel if you need automatic accountability
- A `users` database table

Recommended:

- [laravel-activitylog](http://www.github.com/pierresilva/laravel-activitylog) (For tracking Category and Location changes to stocks)

### Benefits

If you're using the traits from this package to customize your install, that means you have complete flexibility over your own
models, methods (excluding relationship names/type), database tables, property names, and attributes. You can set your
own base model, and if you don't like the way a method is performed just override it.

## Tanks
[stevebauman](https://github.com/stevebauman)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

