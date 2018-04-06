# Languages

In order of completion goal.

* PHP
* C# (Unity)
* C++

# Assets

* Library - Just the DOSE code library files in a given language. Use this for project integration.
* Shell - The DOSE shell executable will be precompiled and ready to take your datatables as JSON and provide rolling data without any need for compiling. Using the DOSE shell, you can test all chance-based logic of your software before the first line is even written! Or, maybe you just want to use it for tabletop gaming... that's fine too. If you build the JSON structure, the shell will handle the rest.

# Introduction

The logic of the DOSE engine is relatively simple. As simply as possible, DOSE lets you build reliable data structures that can be used to precisely built very complex chance systems in games and other game-like software. At the heart of every DOSE outcome setup is a Scenario. Within each DOSE **Scenario**, one or more **Tables**. Finally, within those Tables sits one or more **Results**. Tables can be though of as "channels". Each of these will be rolled. Each Table contributes to the Reward stack independently of one another. Meaning, if one table rolls something, the other tables still roll.

Scenarios are told to roll, the tables are incremented with a random Result being picked based on their denominator (ex, a Table with two results with a denominator of 3 would result in 33% chance for each as well as a 33% chance of nothing).

DOSE Tables act as "channels". So, in a way, it's like rolling several dice at once, each time the Scenario is told to roll. Due to the use of the Marsenne Twister randomization algorithm, each Table will roll differently within a Scenario - no earlier Table roll will resemble the outcome of a Table further down the list.

## The Basics

Using DOSE is a very simple three-step process.

1. You must build a Scenario, build the Table(s) inside of that, and the Result(s) inside of those. (The denominator(s) you use are the *single most important part* of the entire DOSE system. The denominator determines the chance Results will be picked, which is the foundation of DOSE.)

2. Call the Roll() method. This will instruct DOSE to "run" your scenario, and populate your currently empty Reward array. (Results become Rewards once rolled.)

3. Access and act upon the Reward array. There are many ways you could do this, including accessing and reading the array as JSON. This is up to you. All the heavy lifting is already done. Once finished, you can even run Roll() again on the same Scenario and completely reset the Reward array.

## How To

### Create a Result
The whole point of DOSE is to get you results in a readable form of data. Appropriately, the first thing that you need to know how to create is a Result. Using PHP as an example language, we will create a very basic Result, which will be explained afterward.


```php
new Result(2.0, "My first result!", DOSE_TYPE_DUMMY, 123, 1, 5, NULL, NULL)
```

In order of parameters:

1. The first parameter is the Denominator. A denominator of 1 (1/1) would be a 100% roll chance. A denominator of 2 (1/2) would be a 50% chance. A denominator of 90 (1/90) would be a 1.11% chance. You get the picture. Imagine your Table as being a very large closet. You can imagine that your Results are boxes, and their hieght is determined by their denominator. You can only stack them so high. If the total sum of the denominator percentages exceeds the maximum of 100%, your table will "pop". This will not cause crashes, it will simply cause DOSE to throw a friendly warning and ignore everything beyond the 100% mark.

2. The second parameter is the name of your result. This will most often not be disclosed to the user, and instead will be used for debug purposes. Of course, you are free to use this in any way you want.

3. This is the TYPE. There is a predefined group of constant integers pre-declared that you can use, but you are free to use bare integers as well. Your own application will interpret the TYPE, so use what works best for your project.

4. This is the ID of the reward. Together with the type, you can determine what exactly has been given back to you. For example, TYPE_ITEM with the ID of 330 would be item 330 from your list of game items.

5. This is the minimum quantity.

6. This is the maximum quantity. The reward array also has a quantity variable, and that's determined both by the quantity roll as well as how many different tables rolled the exact same result, if you decide the same Result should be in multiple tables.

7. Placeholder - 0 by default.

8. Placeholder - 0 by default.

### Create a Table

A Table will contain one or more Results. You  can pre-construct them and include them, or you can construct them within the Table's own constructor. We will be doing the latter. The following will be a table with a "fill" of 100%; an equal (25%) chance of rolling between four different Results.

```php
new Table
(
  "My first table!",
  new Result(4.0, "My first result!", DOSE_TYPE_DUMMY, 123, 1, 5, NULL, NULL), //25% chance to get 1 to 5 of dummy #123
  new Result(4.0, "My second result!", DOSE_TYPE_DUMMY, 124, 2, 8, NULL, NULL), //25% chance to get 2 to 8 of dummy #124
  new Result(4.0, "My third result!", DOSE_TYPE_DUMMY, 125, 1, 1, NULL, NULL), //etc...
  new Result(4.0, "My fourth result!", DOSE_TYPE_DUMMY, 126, 2, 2, NULL, NULL)
)
```

### Create a Scenario

A scenario really is just a combination of tables. Some scenarios may have more than one "fight" going on, or more than one die rolling around.


```php
$myscenario = new Scenario //we're actually putting it into a var this time
(
  "My first scenario!",
  new Table
  (
    "My first table!",
    new Result(4.0, "My first result!", DOSE_TYPE_DUMMY, 123, 1, 5, NULL, NULL), //25% chance to get 1 to 5 of dummy #123
    new Result(4.0, "My second result!", DOSE_TYPE_DUMMY, 124, 2, 8, NULL, NULL), //25% chance to get 2 to 8 of dummy #124
    new Result(4.0, "My third result!", DOSE_TYPE_DUMMY, 125, 1, 1, NULL, NULL), //etc...
    new Result(4.0, "My fourth result!", DOSE_TYPE_DUMMY, 126, 2, 3, NULL, NULL)
  ),
  new Table
  (
    "My second table!",
    new Result(20.0, "Rarer result 1", DOSE_TYPE_DUMMY, 155, 1, 1, NULL, NULL), //5%
    new Result(30.0, "Rarer result 2", DOSE_TYPE_DUMMY, 166, 1, 1, NULL, NULL) //3.33%
  )
)
```

The Scenario is complete! Now you need to actually run the Roll() function:

```php
$myscenario->Roll();
```

The roll worked! View the results like so:

```php
$myscenario->PrintRewardJSON();
```

The Reward stack may look something like:

1. Dummy 126 x2
2. Dummy 155 x1


## Examples

### Minecraft Zombie

As described by the [Minecraft wiki](https://minecraft.gamepedia.com/Zombie#Drops). Item IDs are dummies.

```php
$DsMinecraftZombie = new Scenario
(
  "Minecraft zombie",
  new Table
  (
    "Flesh",
    new Result(1.0, "0-2 Rotten flesh", DOSE_TYPE_ITEM, 991, 0, 2, NULL, NULL)
  ),
  new Table
  (
    "Special drops",
    new Result(125.0, "Iron ingot", DOSE_TYPE_ITEM, 609, 1, 1, NULL, NULL), //0.08% chance
    new Result(125.0, "Carrot", DOSE_TYPE_ITEM, 344, 1, 1, NULL, NULL),
    new Result(125.0, "Potato", DOSE_TYPE_ITEM, 345, 1, 1, NULL, NULL)
  )
)

if($lootingEnchantment == 1) //lvl 1 Looting
{
  $DsMinecraftZombie->EditDenominator("Iron ingot", 83.33); //modify to 1.2%
  $DsMinecraftZombie->EditDenominator("Carrot", 83.33);
  $DsMinecraftZombie->EditDenominator("Potato", 83.33);
}

else if($lootingEnchantment == 2) //lvl 2 Looting
{
  $DsMinecraftZombie->EditDenominator("Iron ingot", 66.66); //modify to 1.5%
  $DsMinecraftZombie->EditDenominator("Carrot", 66.66);
  $DsMinecraftZombie->EditDenominator("Potato", 66.66);
}

else if($lootingEnchantment == 3) //lvl 3 Looting
{
  $DsMinecraftZombie->EditDenominator("Iron ingot", 55.55); //modify to 1.8%
  $DsMinecraftZombie->EditDenominator("Carrot", 55.55);
  $DsMinecraftZombie->EditDenominator("Potato", 55.55);
}
```

### Runescape Chicken

The following DOSE Scenario will be a 1:1 re-creation of Runescape's Chicken drop table according to [its wiki](http://oldschoolrunescape.wikia.com/wiki/Chicken). Item IDs are dummies.

```php
$DsRunescapeChicken = new Scenario
(
  "Runescape chicken",
  new Table
  (
    "Feather table",
    new Result(1.2, "5-15 Feathers", DOSE_TYPE_ITEM, 906, 5, 15, NULL, NULL) //83% chance of 5-15 feathers
  ),
  new Table
  (
    "100% bones",
    new Result(1.0, "Bones", DOSE_TYPE_ITEM, 56, 1, 1, NULL, NULL) //always drop 1 bones
  ),
  new Table
  (
    "100% meat",
    new Result(1.0, "Raw chicken", DOSE_TYPE_ITEM, 1123, 1, 1, NULL, NULL) //always drop 1 raw chicken
  )
)
```
