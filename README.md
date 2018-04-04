# Languages

In order of completion goal.

* PHP
* C# (Unity)
* C++

# Assets

* Library - Just the DOSE code library files in a given language. Use this for project integration.
* Shell - The DRESS shell will be precompiled and ready to take your datatables as JSON and provide rolling data without any need for compiling. Using the DOSE shell, you can test all chance-based logic of your software before the first line is even written! Or, maybe you just want to use it for tabletop gaming... that's fine too. If you build the JSON structure, the shell will handle the rest.

# Examples

The logic of the DOSE engine is relatively simple. At the heart of every DOSE outcome setup is a "Scenario". Within each DOSE Scenario, a DOSE "Table". Finally, within those Tables sit "Results". Scenarios are told to roll, the tables are incremented with a random Result being picked based on their denominator (ex, a Table with two results with a denominator of 3 would result in 33% chance for each as well as a 33% chance of nothing).

DOSE Tables act as "channels". So, in a way, it's like rolling several dice at once, each time the Scenario is told to roll. Due to the use of the Marsenne Twister randomization algorithm, each Table will roll differently within a Scenario - no earlier Table roll will resemble the outcome of a Table further down the list.

## PHP

### Minecraft

```php
$my_scenario = new Scenari
(

);
```

### Runescape
