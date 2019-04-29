Easy Author Image Plugin for WordPress
=================

Hello and welcome to the Git repository for the open source plugin Easy Author Image, a profile enhancement for WordPress installations that gives you a profile picture field in your profile settings.

=== Plugin Name ===

Contributors: Lawsonry

Tags: author image, profile, avatar

Requires at least: 3.0.1

Tested up to: 5.2

Stable tag: 1.0

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds an author image uploader to your profile page. Upload an author image right from your profile page with the click of a button.

== Description ==

Easy Author Image gives you the option do you exactly that -- easily add an author image to your profile page. Once you install this plugin, you'll have a new option in the Your Profile tab (under Users) called Profile Picture, complete with a media uploader box that allows you to upload your very own author picture. 

Whether you call it an avatar, an author image, or an author picture, Easy Author Image is a "Works Out Of The Box (WOOT-B)" solution to a common problem.

== Installation ==

1. Download the plugin from the plugin directory. 
2. Navigate to Plugins, then click on Add New. 
3. Click on Upload.
4. Find the file you downloaded (easy-author-image.zip) and upload it.
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Go to Users->Your Profile and get started! If you have multiple users, they all can log in and upload an image, too. 

== Frequently Asked Questions ==

= How exactly do I get started? =

* Go to Users->Your Profile. 
* Find the new Profile Picture section.
* Click on the button that says "Upload New Author Profile Image"
* Use the media uploader to either Upload a new picture, Link to a URL of a picture, or select an image from your Media Gallery.
* Click on the picture you want to use and then click on the button at the bottom of the media uploader that says "Make this my author profile picture!"
* IMPORTANT: You MUST save the changes by clicking the Update Profile button at the bottom of Your Profile. If you don't update your profile your author image will not be uploaded/changed.

= I selected an author image but I don't see it in the preview. What happened? =

You forgot to save your profile after you uploaded the picture. Go back and reupload or reselect your author image and then once you click on the Make This My Author Profile Picture button, click on the blue Update Profile button at the bottom of your profile page. 

= How do I display an author's image? =

Easy Author Image comes with a function that will return the url of the author's image. The function is called get_author_image_url().

If you want to display the author image in the loop, do something like this:

`$avatar = get_author_image_url(); // The function uses get_the_ID() to grab the appropirate user ID for the author image.
$name = get_the_author_meta('display_name');
$html = '<img src="$avatar" alt="A Picture of $name"/>';
echo $html;`
  
If you want to display the author image somewhere not in the loop, do something like this:

`$avatar = get_author_image_url('123'); // Replace 123 with the id of the author you want to use
$name = get_the_author_meta('display_name');
$html = '<img src="$avatar" alt="A Picture of $name"/>';
echo $html;`

Since Easy Author Image adds a new custom field to the user profile, you can also use WordPress's internal functionality to retrieve the author image url as well:

`$url = get_the_author_meta('author_profile_picture', $user_id);`

= Does it matter what size the image is? =

This plugin only deals with uploading and retrieving the image. It's up to you to format it appropriately to your theme. 

= I get it. So the get_author_image_url() function is basically just a pre-formatted call to get_the_author_meta('author_profile_picture', $user_id), right? =

Correct!

== Screenshots ==

1. Step 1: Navigate to Your Profile. 
2. Step 2: The new Profile Picture field on your authors' profile pages. Click on that button that says "Upload new author profile picture." Don't worry -- in addition to uploading a new picture, you can also select an image from a URL or one already uploaded to your Media Gallery.  
3. Step 3: As you can see, it uses the same Media Uploader as your Media Library does. Find a picture you want to use as your author profile picture. 
4. Step 4: Click on this button once your image is uploaded/selected.
5. Step 5: Update your profile!
6. Step 6: Once your profile has been updated, revel in the fantasticity of your selection.

== Changelog ==

= 1.0 =
* Released.

== Upgrade Notice ==

None at this time.
