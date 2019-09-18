# Exec Scripts

### Description

Scripts execution from Admin Dashboard

### Features
[1.0.0]
* Scripts execution from Admin Dashboard
* Add scripts in your theme

### Actions

**`exec_scripts_install`**

###### Definition:

Exec action on plugin installation

###### Example:

```
function clrz_exec_scripts_install(  ) {
    // Do something
}
add_action( 'clrz_exec_scripts_install', 'on_clrz_exec_scripts_install', 10, 1 );
```


### Add scripts in your theme
Create file **ajaxThemeController.php** in _your-theme/exec-scripts/src/_ with following code : 
```
<?php
/**
 * Class ajaxThemeController
 */
class ajaxThemeController {

    //########################//
    //##      ACTIONS       ##//
    //########################//

    public function [NAME_OF_YOUR_ACTION]Action(){
        // your code

        die;
    }
}
```
