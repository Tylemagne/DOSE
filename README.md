# Languages

In order of completion goal.

* PHP
* C# (Unity)
* C++

# Assets

* Library - Just the DOSE code library files in a given language. Use this for project integration.
* Shell - The DRESS shell will be precompiled and ready to take your datatables as JSON and provide rolling data without any need for compiling. Using the DOSE shell, you can test all chance-based logic of your software before the first line is even written! Or, maybe you just want to use it for tabletop gaming... that's fine too. If you build the JSON structure, the shell will handle the rest.

# Introduction

The logic of the DOSE engine is relatively simple. As simply as possible, DOSE lets you build reliable data structures that can be used to precisely built very complex chance systems in games and other game-like software. At the heart of every DOSE outcome setup is a Scenario. Within each DOSE **Scenario**, one or more **Tables**. Finally, within those Tables sits one or more **Results**.

Scenarios are told to roll, the tables are incremented with a random Result being picked based on their denominator (ex, a Table with two results with a denominator of 3 would result in 33% chance for each as well as a 33% chance of nothing).

DOSE Tables act as "channels". So, in a way, it's like rolling several dice at once, each time the Scenario is told to roll. Due to the use of the Marsenne Twister randomization algorithm, each Table will roll differently within a Scenario - no earlier Table roll will resemble the outcome of a Table further down the list.

## The Basics

Using DOSE is a very simple three-step process.

1. You must build a Scenario, build the Table(s) inside of that, and the Result(s) inside of those. (The denominator(s) you use are the *single most important part* of the entire DOSE system. The denominator determines the chance Results will be picked, which is the foundation of DOSE.)

2. Call the Roll() method. This will instruct DOSE to "run" your scenario, and populate your currently empty Reward array. (Results become Rewards once rolled.)

3. Access and act upon the Reward array. There are many ways you could do this, including accessing and reading the array as JSON. This is up to you. All the heavy lifting is already done. Once finished, you can ever run Roll() again on the same Scenario and completely reset the Reward array.

The whole point of DOSE is to get you results in a readable form of data. Appropriately, the first thing that you need to know how to create is a Result. Using PHP as an example language, we will create a very basic Result with a 50% chance of being rolled from the table it's contained in.


```php
new Result();
```


### Minecraft


### Runescape
