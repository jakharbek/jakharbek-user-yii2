Basic Functionality
===================
Basic Functionality

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/jakharbek-user "*"
```

or add

```
"jakharbek/jakharbek-user": "*"
```

to the require section of your `composer.json` file.


Usage
-----
You must migrate the database from the / migration folder

Once the extension is installed, you mast configurate extension;
To do this, you need to open the src/bootstrap.php
And specify the mail component and your email

    public static $mailer_component = "mailer";
    public static $email_from = "";

You can specify where to register the controller in which routes

	public static $controllers = [
        'registration_page' => ['test/reg','reg'],
        'login_page' => ['test/login','login']
        ];

You must specify where the user and administrator modules will be registered:

	public static $modules = [
        'test/admin' => 'jakharbek\user\modules\admin\Module',
        'test/user' => 'jakharbek\user\modules\user\Module'
    ];

You must specify where to enter the redirect when you log in.

	public static $redirect_after_login_admin = ['/test/admin'];
	public static $redirect_after_login = ['/test/user'];
 
 You must specify the permission to log in
 
	public static $premission_admin_panel = "controlPanel";
 
 You must provide a link to the user and administrator profile
 
	public static $profile_link = '/test/user/profile/';
    public static $profile_link_admin = '/test/admin/profile/';
	
