### ShowCase
---

[Demo](http://www.shanehoban.com/showcase/)

ShowCase is a free (as in beer), open source web app for displaying your projects effortlessly on the web.

Here's what you need to use ShowCase:

- Apache (web server)
- PHP
- MySQL

For now, in order to use ShowCase, you must also self-host it. There may be a web based version that I set up in the future that will be available publicly.

---

#### Installation

Installation in theory should be quite simple (as usual), but in practice likely won't be (as usual).

I've tried to make it as easy as possible to set up ShowCase on your own server.

The following steps should do it:

1. Download the latest version of ShowCase (GitHub)
2. Update `inc/config.php` with your MySQL connection details
3. Now put all this stuff in a new folder e.g. `/showcase/` on your server
4. In your browser, simply navigate to that new folder
  e.g. `http://www.yourdomain.com/showcase/`
5. Follow the steps on screen

---

#### Updating ShowCase

Just do a `git pull origin master` and push to your server - ya dingus.

Psst... Don't forget to ensure your `config.php` is up to date after a pull!

---

##### Managing Projects

![](http://i.imgur.com/v86oT7t.png)

---

##### ShowCase Settings

![](http://i.imgur.com/Sb5zphX.png)
