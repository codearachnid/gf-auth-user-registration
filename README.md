Gravity Forms Authenticate User
=============

This is a secondary layer to "force" a user registration to verify their account which will move account from one specified account role to another. 

### Dependancies
* [Gravity Forms](http://www.gravityforms.com)
* [Gravity Forms User Registration Add-On](http://www.gravityforms.com/add-ons/user-registration/). 

Installation
-----------

1. Download plugin
2. Upload to your plugin directory
3. Activate Gravity Forms & Gravity Forms User Registration Addon
4. Activate this plugin

Configuration
-----

Create/update user registration form
Add hidden field (label can be set to anything), under "Advanced" tab select populate this field dynamically
Insert "public_key" in the field that is shown
Save form
Click on "Form Notifications"
Create message response (to either admin or user) add the following link structure http://yourdomain.tld/validate-user/{public_key}/
Use the dropdown field tool to supply the public_key value in the link - so that it is properly associated to that field
Create/update page, set the slug to be "validate-user"

Add shortcode to template, content or widget area you want validation messaging to appear
    [gfauthenticateuser]

Define the short code with the following parameters:
    fail="Message you want to deliver if no user is found or bad hash is delivered"
    success="Message you want to deliver once user is upgraded"
    setrole="User role that has access to the area you want to give"
    currentrole="User role that you specified in gravity forms when they signed up"

Change Log
------------

2012-02-10: First release
