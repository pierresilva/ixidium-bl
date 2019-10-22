# Log activity inside your Laravel app

The `pierresilva/laravel-activitylog` package provides easy to use functions to log the activities of the users of your app. It can also automatically log model events. 
The Package stores all activity in the `activity_log` table.

Here's a demo of how you can use it:

```php
activity()->log('Look, I logged something');
```

You can retrieve all activity using the `pierresilva\Activitylog\Models\Activity` model.

```php
Activity::all();
```

Here's a more advanced example:
```php
activity()
   ->performedOn($anEloquentModel)
   ->causedBy($user)
   ->withProperties(['customProperty' => 'customValue'])
   ->log('Look, I logged something');
   
$lastLoggedActivity = Activity::all()->last();

$lastLoggedActivity->subject; //returns an instance of an eloquent model
$lastLoggedActivity->causer; //returns an instance of your user model
$lastLoggedActivity->getExtraProperty('customProperty'); //returns 'customValue'
$lastLoggedActivity->description; //returns 'Look, I logged something'
```

```php
$newsItem->name = 'updated name';
$newsItem->save();

//updating the newsItem will cause the logging of an activity
$activity = Activity::all()->last();

$activity->description; //returns 'updated'
$activity->subject; //returns the instance of NewsItem that was created
```

Calling `$activity->changes()` will return this array:

```php
[
   'attributes' => [
        'name' => 'updated name',
        'text' => 'Lorum',
    ],
    'old' => [
        'name' => 'original name',
        'text' => 'Lorum',
    ],
];
```

## Installation

You can install the package via composer:

``` bash
composer require pierresilva/laravel-activitylog
```

The package will automatically register itself.

You can publish the migration with:
```bash
php artisan vendor:publish --provider="pierresilva\Activitylog\ActivitylogServiceProvider" --tag="migrations"
```

*Note*: The default migration assumes you are using integers for your model IDs. If you are using UUIDs, or some other format, adjust the format of the subject_id and causer_id fields in the published migration before continuing.

After publishing the migration you can create the `activity_log` table by running the migrations:


```bash
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="pierresilva\Activitylog\ActivitylogServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /**
     * When set to false, activitylog will not 
     * save any activities to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /**
     * Running the clean-command will delete all activities
     * older than the number of days specified here.
     */
    'delete_records_older_than_days' => 365,


    /**
     * When not specifying a log name when logging activity
     * we'll using this log name.
     */
    'default_log_name' => 'default',


    /**
     * When set to true, the subject returns soft deleted models.
     */
     'subject_returns_soft_deleted_models' => false,
     
     
    /**
     * The model used to log the activities. 
     * It should be or extend the pierresilva\Activitylog\Models\Activity model.
     */
    'activity_model' => \pierresilva\Activitylog\Models\Activity::class,     
];
```

### Logging activity

This is the most basic way to log activity:

```
activity()->log('Look mum, I logged something');
```

You can retrieve the activity using the ```pierresilva\Activitylog\Models\Activity``` model.

```
$lastActivity = Activity::all()->last(); //returns the last logged activity
$lastActivity->description; //returns 'Look mum, I logged something';
```

#### Setting a subject
You can specify on which object the activity is performed by using performedOn:

```
activity()
   ->performedOn($someContentModel)
   ->log('edited');

$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->subject; //returns the model that was passed to `performedOn`;
```

The performedOn-function has a shorter alias name: ```on```

#### Setting a causer
You can set who or what caused the activity by using causedBy:

```
activity()
   ->causedBy($userModel)
   ->performedOn($someContentModel)
   ->log('edited');
   
$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->causer; //returns the model that was passed to `causedBy`;
```   

The causedBy()-function has a shorter alias named: ```by```

If you're not using causedBy the package will automatically use the logged in user.

#### Setting custom properties
You can add any property you want to an activity by using ```withProperties```

```
activity()
   ->causedBy($userModel)
   ->performedOn($someContentModel)
   ->withProperties(['key' => 'value'])
   ->log('edited');
   
$lastActivity = Activity::all()->last(); //returns the last logged activity
   
$lastActivity->getExtraProperty('key') //returns 'value';  

$lastActivity->where('properties->key', 'value')->get(); // get all activity where the `key` custom property is 'value'
```

### Cleaning up the log
After using the package for a while you might have recorded a lot of activity. This package provides an artisan command ```activitylog:clean``` to clean the log.

Running this command will result in the deletion of all recorded activity that is older than the number of days specified in the ```delete_records_older_than_days``` of the config file.

You can leverage Laravel's scheduler to run the clean up command now and then.

```
//app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
   $schedule->command('activitylog:clean')->daily();
}
```

### Logging model events
A neat feature of this package is that it can automatically log events such as when a model is created, updated and deleted. To make this work all you need to do is let your model use the ```pierresilva\Activitylog\Traits\LogsActivity-trait```.

As a bonus the package will also log the changed attributes for all these events when setting ```$logAttributes``` property on the model.

The attributes that need to be logged can be defined either by their name or you can put in a wildcard ```'*'``` to log any attribute that has changed.

Here's an example:

```
use Illuminate\Database\Eloquent\Model;
use pierresilva\Activitylog\Traits\LogsActivity;

class NewsItem extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'text'];
    
    protected static $logAttributes = ['name', 'text'];
}
```

If you want to log changes to all the ```$fillable``` attributes of the model, you can specify protected static ```$logFillable = true;``` on the model. Let's see what gets logged when creating an instance of that model.

```
$newsItem = NewsItem::create([
   'name' => 'original name',
   'text' => 'Lorum'
]);

//creating the newsItem will cause an activity being logged
$activity = Activity::all()->last();

$activity->description; //returns 'created'
$activity->subject; //returns the instance of NewsItem that was created
$activity->changes(); //returns ['attributes' => ['name' => 'original name', 'text' => 'Lorum']];
```

Now let's update some that ```$newsItem```.

```
$newsItem->name = 'updated name'
$newsItem->save();

//updating the newsItem will cause an activity being logged
$activity = Activity::all()->last();

$activity->description; //returns 'updated'
$activity->subject; //returns the instance of NewsItem that was created
```

Calling ```$activity->changes()``` will return this array:

```
[
   'attributes' => [
        'name' => 'updated name',
        'text' => 'Lorum',
    ],
    'old' => [
        'name' => 'original name',
        'text' => 'Lorum',
    ],
];
```

Now, what happens when you call delete?

```
$newsItem->delete();

//deleting the newsItem will cause an activity being logged
$activity = Activity::all()->last();

$activity->description; //returns 'deleted'
$activity->changes(); //returns ['attributes' => ['name' => 'updated name', 'text' => 'Lorum']];
```

### Customizing the events being logged
By default the package will log the created, updated, deleted events. You can modify this behaviour by setting the ```$recordEvents``` property on a model.

```
use Illuminate\Database\Eloquent\Model;
use pierresilva\Activitylog\Traits\LogsActivity;

class NewsItem extends Model
{
    use LogsActivity;

    //only the `deleted` event will get logged automatically
    protected static $recordEvents = ['deleted'];
}
```

### Customizing the description
By default the package will log created, updated, deleted in the description of the activity. You can modify this text by overriding the ```getDescriptionForEvent``` function.

```
use Illuminate\Database\Eloquent\Model;
use pierresilva\Activitylog\Traits\LogsActivity;

class NewsItem extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'text'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

}
```

Let's see what happens now:

```
$newsItem = NewsItem::create([
   'name' => 'original name',
   'text' => 'Lorum'
]);

//creating the newsItem will cause an activity being logged
$activity = Activity::all()->last();

$activity->description; //returns 'This model has been created'
```

### Customizing the log name
Specify $logName to make the model use another name than the default.

```
use Illuminate\Database\Eloquent\Model;
use pierresilva\Activitylog\Traits\LogsActivity;

class NewsItem extends Model
{
    use LogsActivity;

    protected static $logName = 'system';
}
```

### Ignoring changes to certain attributes
If your model contains attributes whose change don't need to trigger an activity being logged you can use ```$ignoreChangedAttributes```

```
use Illuminate\Database\Eloquent\Model;
use pierresilva\Activitylog\Traits\LogsActivity;

class NewsItem extends Model
{
    use LogsActivity;
    
    protected static $ignoreChangedAttributes = ['text'];

    protected $fillable = ['name', 'text'];
    
    protected static $logAttributes = ['name', 'text'];
}
```

### Changing text will not trigger an activity being logged.

By default the ```updated_at``` attribute is not ignored and will trigger an activity being logged. You can simply add the ```updated_at``` attribute to the ```$ignoreChangedAttributes``` array to override this behaviour.

### Using the CausesActivity trait
The package ships with a ```CausesActivity``` trait which can be added to any model that you use as a causer. It provides an ```activity``` relationship which returns all activities that are caused by the model.

If you include it in the ```User``` model you can simply retrieve all the current users activities like this:

```
\Auth::user()->activity;
```

### Using LogsActivity and CausesActivity on the same model

To log activity for a model that can also cause activity, use the ```HasActivity``` trait. It provides an ```activity``` relationship which is identical to ```LogsActivity``` and an ```actions``` relationship for any activity caused by the model.

For example, if you include it in the ```User``` model you can see all the ```activity``` on that model performed by any user by using ```activity``` but also all changes caused by the user on any models using ```actions```.

### Disabling logging on demand
You can also disable logging for a specific model at runtime. To do so, you can use the ```disableLogging()``` method:

```
$newsItem = NewsItem::create([
   'name' => 'original name',
   'text' => 'Lorum'
]);

// Updating with logging disabled
$newsItem->disableLogging();

$newsItem->update(['name' => 'The new name is not logged']);
```

You can also chain ```disableLogging()``` with the ```update()``` method.

### Enable logging again
You can use the ```enableLogging()``` method to re-enable logging.

```
$newsItem = NewsItem::create([
   'name' => 'original name',
   'text' => 'Lorum'
]);

// Updating with logging disabled
$newsItem->disableLogging();

$newsItem->update(['name' => 'The new name is not logged']);

// Updating with logging enabled
$newsItem->enableLogging();

$newsItem->update(['name' => 'The new name is logged']);
```

### Using placeholders
When logging an activity you may use placeholders that start with ```:subject```, ```:causer``` or ```:properties```. These placeholders will get replaced with the properties of the given subject, causer or property.

Here's an example:

```
activity()
    ->performedOn($article)
    ->causedBy($user)
    ->withProperties(['laravel' => 'awesome'])
    ->log('The subject name is :subject.name, the causer name is :causer.name and Laravel is :properties.laravel');

$lastActivity = Activity::all()->last();
$lastActivity->description; //returns 'The subject name is article name, the causer name is user name and Laravel is awesome';
```

## Using multiple logs
### The default log
Without specifying a log name the activities will be logged on the default log.

```
activity()->log('hi');

$lastActivity = pierresilva\Activitylog\Models\Activity::all()->last();

$lastActivity->log_name; //returns 'default';
```

You can specify the name of the default log in the ```default_log_name``` key of the config file.

### Specifying a log
You can specify the log on which an activity must be logged by passing the log name to the ```activity``` function:

```
activity('other-log')->log("hi");

Activity::all()->last()->log_name; //returns 'other-log';
```

### Specifying a log for each model
By default, the ```LogsActivity``` trait uses ```default_log_name``` from the config file to write the logs. You can set a different log for each model by setting the ```$logName``` property on the model.

```
protected static $logName = 'custom_log_name_for_this_model';
```

### Retrieving activity
The ```Activity``` model is just a regular Eloquent model that you know and love:

```
Activity::where('log_name' , 'other-log')->get(); //returns all activity from the 'other-log'
```

There's also an ```inLog``` scope you can use:

```
Activity::inLog('other-log')->get();

//you can pass multiple log names to the scope
Activity::inLog('default', 'other-log')->get();

//passing an array is just as good
Activity::inLog(['default', 'other-log'])->get();
```
## Testing

``` bash
$ composer test
```

## Tanks

[Spatie](https://github.com/spatie)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
