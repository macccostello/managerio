# Managerio
API Client of Manager.io (PHP)

#Installing Managerio

The recommended way to install Managerio is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install Managerio:

```bash
composer require kennnethmervin01/managerio dev-master
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

# Sample Usage
``` php
use Managerio\Connect;

// Set credential
$auth = array(
	"username" =>  "yourusername", 
	"password" =>  "yourpassword", 
	"home"     =>  "http://yourUrl/api",
	"base_uri" =>  "http://yourUrl/api/",
	"business" =>  "yourBusinessName",
	"businessID" => "yourBusinessID"   
);
// Connect to your server
$test = new Connect($auth);


// sample array to add customer
$customer = array(
				"Name" => "Bruce Wayne XXIX",
		  		"BillingAddress" => "Gotham",
		  		"Email" =>  "baty4@gmail.com",
		  		"BusinessIdentifier" =>  "001 001 511",
		  		"StartingBalanceType" => "Credit"
);

//Add Customer
$test->addCustomer($customer);
```
