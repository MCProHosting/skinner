# Skinner

[![Build Status](https://travis-ci.org/MCProHosting/skinner.svg)](https://travis-ci.org/MCProHosting/skinner)

Skinner is a fairly simple library to grab Minecraft heads and skins for your application.

## Installation

Add this to your composer.json file's requires, and run `composer update`:

```
"mcprohosting/skinner": "dev-master"
```

## Usage

```
// Example 1: save a player's skin and head to a file:
file_put_contents('steve_skin.png', Skinner::user('steve')->skin());
file_put_contents('steve_skin.png', Skinner::user('steve')->head());

// Or, more efficiently, in a way that will only cause the skin to be downloaded once

$user = Skinner::user('steve');
file_put_contents('steve_skin.png', $user->skin());
file_put_contents('steve_skin.png', $user->head());

// Calls to the $user are automatically passed to Intervention images. So you can, for example:

$user = Skinner::user('steve');
file_put_contents('steve_skin.png', $user->skin()->resize(300, 300));

// Or, manually save it in another format, like jpg at 70% quality

$user = Skinner::user('steve');
file_put_contents('steve_skin.jpg', $user->encode('jpg', 70));
```