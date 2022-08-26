# Additonal Field Plugin

### Installation Steps for the Additional Fields for Shopware 6

### **1 From the command line:-**

  1. Copy the DdmsAdditionalField plugin the paste to the this custom > plugins on the server.

  2. Go to your Shopware installation root directory and run this command –

  >   php bin/console plugin:install DdmsAdditionalFields

  3. To activate the plugin run this command –

  >   php bin/console plugin:active DdmsAdditionalFields

### **2 From the backend ( admin End)**

  1. Goto your Shopware 6 installation backend panel after that navigate to Extensions -> My Extensions after that you can find all the installed plugin in it.

  2. For installing the plugin, the user can click on the Upload Extension button. The user can upload the plugin zip(DdmsAdditionalFields) here.

  3. After uploading the plugin zip, the user can see the plugin in the list

  4. Now the user can click on the install icon to install the Additonal Fields Upload plugin.

  5. After the installation of the plugin, the user can click to activate the plugin.

### **Backend Settings**

  After the complete installation of Additional Fields and activation of the plugin.

  * To set custom field of title and description for **Product**, admin will navigate to **Catalogues > Products**.
  * Under the product listing, Goto the product detail page of product.
  * And then select the specifications tab and the specifications tab you will find the Custom Fields section. Under the custom fields section, click on the Additional Fields Product tab.
  * Lastly, set the custom title and description for the product.


  **Note:- If custom title and description is set then it will show on the frontend in the product detail page otherwise it take the default name  and description of the product.**


  * To set the custom field of title and description for Categories, admin will navigate to Catalogues > Categories.
  * Under the categories listing page, select the category to set the custom field.
  * In the category detail page, you will find the custom fields section. Under the custom fields section, Click on the Additional Fields Categories.
  * Lastly, set the custom title and description for the category.  

  **Note:- If custom title and description is set then it will show on the frontend in the category page otherwise it take the default name  and description of the category.**

  **Note:- No other configuration is required.**