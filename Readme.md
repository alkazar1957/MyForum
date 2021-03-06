# MyForum

This is an open source forum that was built and maintained at Laracasts.com.
I am using it to get back into Laravel after a two year break so, install at your own risk!

It doesn't have the typeAhead search nor does it use agolia for the search.
Instead it uses <a href="teamtnt/laravel-scout-tntsearch-driver">TNTSearch Driver for Laravel Scout</a>

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:alkazar1957/MyForum.git
cd MyForum && composer install && npm install
php artisan MyForum:install
npm run dev
```

### Step 2

Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to `http://Myforum.test`. 

1. Visit: `http://MyForum.test/register` to register a new forum account.
