OpenPostcodeDB CakePHP Plugin
=============================

Use in conjunction with http://postcodedb.sourceforge.net/

Importing Data from (http://postcodedb.sourceforge.net/)

How to Use
 - Add this repo as a Plugin at 'app/Plugin/PostcodeDB'
 - Load the plugin, add one of the following lines to 'app/Config/bootstrap.php'
   - CakePlugin::load('PostcodeDB');
   - CakePlugin::loadAll();
 - Generate the required database table
   - app/Console/cake schema create --plugin PostcodeDB
 - Import latest data from (http://postcodedb.sourceforge.net/)
   - app/Console/cake PostcodeDB.UpdatePostcodes

CLI Options
 - -f --flush-table : Flush the lookup table of its current contents and re-import from fresh