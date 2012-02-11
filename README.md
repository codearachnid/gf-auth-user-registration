Gravity Forms Authenticate User
=============

This is a secondary layer to "force" a user registration to verify their account which will move account from one specified account role to another. 

Dependancies
------------

* [Gravity Forms](http://www.gravityforms.com)
* [Gravity Forms User Registration Add-On](http://www.gravityforms.com/add-ons/user-registration/). 

Contributing
------------

Want to contribute? Great! There are two ways to add markups.


### Commands

If your markup is in a language other than Ruby, drop a translator
script in `lib/github/commands` which accepts input on STDIN and
returns HTML on STDOUT. See [rest2html][r2h] for an example.

Once your script is in place, edit `lib/github/markups.rb` and tell
GitHub Markup about it. Again we look to [rest2html][r2hc] for
guidance:

    command(:rest2html, /re?st(.txt)?/)

Here we're telling GitHub Markup of the existence of a `rest2html`
command which should be used for any file ending in `rest`,
`rst`, `rest.txt` or `rst.txt`. Any regular expression will do.

Finally add your tests. Create a `README.extension` in `test/markups`
along with a `README.extension.html`. As you may imagine, the
`README.extension` should be your known input and the
`README.extension.html` should be the desired output.

Now run the tests: `rake`

If nothing complains, congratulations!


### Classes

If your markup can be translated using a Ruby library, that's
great. Check out Check `lib/github/markups.rb` for some
examples. Let's look at Markdown:

    markup(:markdown, /md|mkdn?|markdown/) do |content|
      Markdown.new(content).to_html
    end

We give the `markup` method three bits of information: the name of the
file to `require`, a regular expression for extensions to match, and a
block to run with unformatted markup which should return HTML.

If you need to monkeypatch a RubyGem or something, check out the
included RDoc example.

Tests should be added in the same manner as described under the
`Commands` section.


Installation
-----------

1. Download plugin
2. Upload to your plugin directory
3. Activate Gravity Forms & Gravity Forms User Registration Addon
4. Activate this plugin

Usage
-----

1. Create/update user registration form
2. Add hidden field (label can be set to anything), under "Advanced" tab select populate this field dynamically
3. Insert "public_key" in the field that is shown
4. Save form
5. Click on "Form Notifications"
6. Create message response (to either admin or user) add the following link structure http://yourdomain.tld/validate-user/{public_key}/
7. Use the dropdown field tool to supply the public_key value in the link - so that it is properly associated to that field
8. Create/update page, set the slug to be "validate-user"

9. Add shortcode to template, content or widget area you want validation messaging to appear
	[gfauthenticateuser]

10. Define the short code with the following parameters:
    fail="Message you want to deliver if no user is found or bad hash is delivered"
    success="Message you want to deliver once user is upgraded"
    setrole="User role that has access to the area you want to give"
    currentrole="User role that you specified in gravity forms when they signed up"

Change Log
------------

2012-02-10: First release
