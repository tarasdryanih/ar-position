ActiveRecord Position Extension for Yii2
========================================

This extension provides support for ActiveRecord custom records order setup.

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yii2tech/ar-position/v/stable.png)](https://packagist.org/packages/yii2tech/ar-position)
[![Total Downloads](https://poser.pugx.org/yii2tech/ar-position/downloads.png)](https://packagist.org/packages/yii2tech/ar-position)
[![Build Status](https://travis-ci.org/yii2tech/ar-position.svg?branch=master)](https://travis-ci.org/yii2tech/ar-position)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2tech/ar-position
```

or add

```json
"yii2tech/ar-position": "*"
```

to the require section of your composer.json.


Usage
-----

This extension provides support for custom records order setup via column-based position index.

This extension provides [[\yii2tech\ar\position\PositionBehavior]] ActiveRecord behavior for such solution
support in Yii2. You may attach it to your model class in the following way:

```php
class Item extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'position',
            ],
        ];
    }
}
```

Behavior uses the specific integer field of the database entity to set up position index.
Due to this the database entity, which the model refers to, must contain field [[positionAttribute]].

In order to display custom list in correct order you should sort it by [[positionAttribute]] in ascending mode:

```php
$records = Item::find()->orderBy(['position' => SORT_ASC])->all();
foreach ($records as $record) {
    echo $record->position . ', ';
}
// outputs: 1, 2, 3, 4, 5,...
```


## Position saving <span id="position-saving"></span>

Being attached behavior automatically fills up `positionAttribute` value fro the new record, plaing it to the end
of the list:

```php
echo Item::find()->count(); // outputs: 4

$item = new Item();
$item->save();

echo $item->position // outputs: 5
```

However you may setup position for the new record explicitly:

```php
echo Item::find()->count(); // outputs: 4

$item = new Item();
$item->position = 2; // enforce position '2'
$item->save();

echo $item->position // outputs: 2 !!!
```


## Position switching <span id="position-switching"></span>

Existing record can be moved to another position using following methods:

 - [[movePrev()]] - moves record by one position towards the start of the list.
 - [[moveNext()]] - moves record by one position towards the end of the list.
 - [[moveFirst()]] - moves record to the start of the list.
 - [[moveLast()]] - moves record to the end of the list.
 - [[moveToPosition()]] - moves owner record to the specific position.

You may as well change record position through the attribute, provided to `positionAttribute` directly:

```php
$item = Item::find()->andWhere(['position' => 3]);
$item->position = 5; // switch position to '5'
$item->save();
```


## Position in group <span id="position-in-group"></span>

Sometimes single database entity contains several listings, which require custom ordering, separated logically
by grouping attributes. For example: FAQ questions may be grouped by categories, while inside single category
questions should be ordered manually. For this case [[\yii2tech\ar\position\PositionBehavior::groupAttributes]]
can be used:

```php
class FaqQuestion extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'position',
                'groupAttributes' => [
                    'categoryId' // multiple lists varying by 'categoryId'
                ],
            ],
        ];
    }
}
```

In this case behavior will use owner values of 'groupAttributes' as additional condition for position
calculation and changing:

```php
echo FaqQuestion::find()->andWhere(['categoryId' => 1])->count(); // outputs: '4'
echo FaqQuestion::find()->andWhere(['categoryId' => 2])->count(); // outputs: '7'

$record = new FaqQuestion();
$record->categoryId = 1;
$record->save();
echo $record->position // outputs: '5'

$record = new FaqQuestion();
$record->categoryId = 2;
$record->save();
echo $record->position // outputs: '8'
```
